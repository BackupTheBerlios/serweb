<?
/*
 * $Id: phonebook.php,v 1.15 2004/04/04 19:42:14 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

if (isset($_POST['okey_x'])) $okey_x=$_POST['okey_x'];
elseif (isset($_GET['okey_x'])) $okey_x=$_GET['okey_x'];
else $okey_x=null;


class Cphonebook{
	var $id;
	var $fname;
	var $lname;
	var $sip_uri;
	var $status;
	var $aliases;

	function Cphonebook($id, $fname, $lname, $sip_uri, $status='unknown'){
		$this->id=$id;
		$this->fname=$fname;
		$this->lname=$lname;
		$this->sip_uri=$sip_uri;
		$this->status=$status;
	}
}

do{
	if (!$db = connect_to_db($errors)) break;

	if (isset($_GET['dele_id'])){
		$q="delete from ".$config->table_phonebook." where ".
			"username='".$auth->auth["uname"]."' and domain='".$config->realm."' and id=".$dele_id;
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}

        Header("Location: ".$sess->url("phonebook.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if (isset($_GET['edit_id'])){
		$q="select fname, lname, sip_uri from ".$config->table_phonebook.
			" where domain='".$config->realm."' and username='".$auth->auth["uname"]."' and id=".$_GET['edit_id'];
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}
		$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();
	}

	$f->add_element(array("type"=>"text",
	                             "name"=>"fname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>isset($row->fname)?$row->fname:"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"lname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>isset($row->lname)?$row->lname:"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"sip_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->sip_uri)?$row->sip_uri:"",
	                             "valid_regex"=>"^".$reg->sip_address."$",
	                             "valid_e"=>"not valid sip address",
								 "extrahtml"=>"onBlur='sip_address_completion(this)' style='width:120px;'"));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"id",
	                             "value"=>isset($_GET['edit_id'])?$_GET['edit_id']:""));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));


	if (!is_null($okey_x)){				// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			/* Process data */           // Data ok;

		if ($id) $q="update ".$config->table_phonebook." set fname='$fname', lname='$lname', sip_uri='$sip_uri' ".
			"where id=$id and domain='".$config->realm."' and username='".$auth->auth["uname"]."'";
		else $q="insert into ".$config->table_phonebook." (fname, lname, sip_uri, username, domain) ".
			"values ('$fname', '$lname', '$sip_uri', '".$auth->auth["uname"]."', '".$config->realm."')";

		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}

        Header("Location: ".$sess->url("phonebook.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while (false);

do{
	$pb_arr=array();
	if ($db){
		// get phonebook
		if (isset($_GET['edit_id'])) $qw=" and id!=".$_GET['edit_id']." "; else $qw="";

		$q="select id, fname, lname, sip_uri from ".$config->table_phonebook.
			" where domain='".$config->realm."' and username='".$auth->auth["uname"]."'".$qw." order by lname";
		$phonebook_res=$db->query($q);
		if (DB::isError($phonebook_res)) {log_errors($phonebook_res, $errors); break;}

		while ($row=$phonebook_res->fetchRow(DB_FETCHMODE_OBJECT)){
			$pb_arr[$row->id] = new Cphonebook($row->id, $row->fname, $row->lname, $row->sip_uri, get_status($row->sip_uri, $db, $errors));
                        $pb_arr[$row->id]->aliases = get_aliases($row->sip_uri, $db, $errors);
		}
		$phonebook_res->free();
	}
}while (false);

if (!is_null($okey_x)){							//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>sip_address_completion.js.php"></script>
<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
$page_attributes['user_name']=get_user_name($db, $errors);
print_html_body_begin($page_attributes);
?>

<div class="swForm">
<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="fname">first name:</label></td>
	<td><?$f->show_element("fname");?></td>
	</tr>
	<tr>
	<td><label for="lname">last name:</label></td>
	<td><?$f->show_element("lname");?></td>
	</tr>
	<tr>
	<td><label for="sip_uri">sip address:</label></td>
	<td><?$f->show_element("sip_uri");?></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","sip_address_completion(f.sip_uri);");					// Finish form?>
</div>

<?if (is_array($pb_arr) and count($pb_arr)){?>
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>name</th>
	<th>sip address</th>
	<th>aliases</th>
	<th>status</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	foreach($pb_arr as $row){
		$odd=$odd?0:1;
		$name=$row->lname;
		if ($name) $name.=" "; $name.=$row->fname;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($name);?></td>
	<td align="left"><a href="javascript: open_ctd_win2('<?echo rawURLEncode($row->sip_uri);?>', '<?echo RawURLEncode("sip:".$auth->auth["uname"]."@".$config->default_domain); ?>');"><?echo $row->sip_uri;?></a></td>
	<td align="left"><?echo nbsp_if_empty(implode(", ", $row->aliases));?></td>
	<td align="center"><?echo nbsp_if_empty($row->status);?></td>
	<td align="center"><a href="<?$sess->purl("phonebook.php?kvrk=".uniqID("")."&edit_id=".$row->id);?>">edit</a></td>
	<td align="center"><a href="<?$sess->purl("phonebook.php?kvrk=".uniqID("")."&dele_id=".$row->id);?>">delete</a></td>
	</tr>
	<?}?>
	</table>
<?}?>

<div class="swLinkToTabExtension"><a href="<?$sess->purl("find_user.php?kvrk=".uniqid(""));?>">find user</a></div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

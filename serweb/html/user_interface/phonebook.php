<?
/*
 * $Id: phonebook.php,v 1.13 2004/03/24 21:39:46 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

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
	$db = connect_to_db();
	if (!$db){ $errors[]="can�t connect to sql server"; break;}

	if ($dele_id){
		$q="delete from ".$config->table_phonebook." where ".
			"username='".$auth->auth["uname"]."' and domain='".$config->realm."' and id=".$dele_id;
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

        Header("Location: ".$sess->url("phonebook.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if ($edit_id){
		$q="select fname, lname, sip_uri from ".$config->table_phonebook.
			" where domain='".$config->realm."' and username='".$auth->auth["uname"]."' and id=".$edit_id;
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		$row=mysql_fetch_object($res);
	}

	$f->add_element(array("type"=>"text",
	                             "name"=>"fname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>$row->fname?$row->fname:"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"lname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>$row->lname?$row->lname:"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"sip_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>$row->sip_uri?$row->sip_uri:"",
	                             "valid_regex"=>"^".$reg->sip_address."$",
	                             "valid_e"=>"not valid sip address",
								 "extrahtml"=>"onBlur='sip_address_completion(this)' style='width:120px;'"));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"id",
	                             "value"=>$edit_id?$edit_id:""));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));


	if (isset($okey_x)){								// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			/* Process data */           // Data ok;

		if ($id) $q="update ".$config->table_phonebook." set fname='$fname', lname='$lname', sip_uri='$sip_uri' ".
			"where id=$id and domain='".$config->realm."' and username='".$auth->auth["uname"]."'";
		else $q="insert into ".$config->table_phonebook." (fname, lname, sip_uri, username, domain) ".
			"values ('$fname', '$lname', '$sip_uri', '".$auth->auth["uname"]."', '".$config->realm."')";

		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}


        Header("Location: ".$sess->url("phonebook.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while (false);

do{
	if ($db){
		// get phonebook
		if ($edit_id) $qw=" and id!=$edit_id "; else $qw="";

		$q="select id, fname, lname, sip_uri from ".$config->table_phonebook.
			" where domain='".$config->realm."' and username='".$auth->auth["uname"]."'".$qw." order by lname";
		$phonebook_res=MySQL_Query($q);
		if (!$phonebook_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		while ($row=MySQL_Fetch_Object($phonebook_res)){
			$pb_arr[$row->id] = new Cphonebook($row->id, $row->fname, $row->lname, $row->sip_uri, get_status($row->sip_uri, $errors));
			$pb_arr[$row->id]->aliases = get_aliases($row->sip_uri, $errors);
		}

	}
}while (false);

if ($okey_x){							//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>sip_address_completion.js.php"></script>
<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
$page_attributes['user_name']=get_user_name($errors);
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

<?if (is_array($pb_arr)){?>
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

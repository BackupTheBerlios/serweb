<?
/*
 * $Id: speed_dial.php,v 1.7 2004/04/04 19:42:14 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

if (isset($_POST['edit_sd'])) $edit_sd=$_POST['edit_sd'];
elseif (isset($_GET['edit_sd'])) $edit_sd=$_GET['edit_sd'];
else $edit_sd=null;

if (isset($_POST['edit_sd_dom'])) $edit_sd_dom=$_POST['edit_sd_dom'];
elseif (isset($_GET['edit_sd_dom'])) $edit_sd_dom=$_GET['edit_sd_dom'];
else $edit_sd_dom=null;


do{
	if (!$db = connect_to_db($errors)) break;

	if (isset($_GET['dele_sd'])){
		$q="delete from ".$config->table_speed_dial." where ".
			"username='".$auth->auth["uname"]."' and domain='".$config->realm."' and username_from_req_uri='".$_GET['dele_sd']."' and domain_from_req_uri='".$_GET['dele_sd_dom']."'";
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}

        Header("Location: ".$sess->url("speed_dial.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if ($edit_sd){
		$q="select username_from_req_uri, domain_from_req_uri, new_request_uri from ".$config->table_speed_dial.
			" where domain='".$config->realm."' and username='".$auth->auth["uname"]."' and username_from_req_uri='".$edit_sd."' and domain_from_req_uri='".$edit_sd_dom."'";
		$res=$db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); break;}
		$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();
	}

	$f->add_element(array("type"=>"text",
	                             "name"=>"usrnm_from_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->username_from_req_uri)?$row->username_from_req_uri:"",
                                     "valid_regex"=>"^".$config->speed_dial_initiation,
                                     "valid_e"=>"username from request uri must start by: ".$config->speed_dial_initiation,
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"domain_from_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->domain_from_req_uri)?$row->domain_from_req_uri:$config->realm,
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"new_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>isset($row->new_request_uri)?$row->new_request_uri:"",
	                             "valid_regex"=>"^".$reg->sip_address."$",
	                             "valid_e"=>"not valid sip address",
								 "extrahtml"=>"onBlur='sip_address_completion(this)' style='width:120px;'"));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"edit_sd",
	                             "value"=>$edit_sd?$edit_sd:""));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"edit_sd_dom",
	                             "value"=>$edit_sd_dom?$edit_sd_dom:""));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));


	if (isset($_POST['okey_x'])){				// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			/* Process data */           // Data ok;

		if ($edit_sd) $q="update ".$config->table_speed_dial." set new_request_uri='$new_uri', username_from_req_uri='$usrnm_from_uri', domain_from_req_uri='$domain_from_uri' ".
			"where username_from_req_uri='$edit_sd' and domain='".$config->realm."' and username='".$auth->auth["uname"]."'";
		else $q="insert into ".$config->table_speed_dial." (username, domain, username_from_req_uri, domain_from_req_uri, new_request_uri) ".
			"values ('".$auth->auth["uname"]."', '".$config->realm."', '$usrnm_from_uri', '$domain_from_uri', '$new_uri')";

		$res=$db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_ALREADY_EXISTS)
				$errors[]="Record with this username and domain already exists";
			else log_errors($res, $errors); 
			break;
		}

        Header("Location: ".$sess->url("speed_dial.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}
}while (false);

do{
	if ($db){
		// get speed dials
		if ($edit_sd) $qw=" and (username_from_req_uri!='$edit_sd' or domain_from_req_uri!='$edit_sd_dom') "; else $qw="";

		$q="select username_from_req_uri, domain_from_req_uri, new_request_uri from ".$config->table_speed_dial.
			" where domain='".$config->realm."' and username='".$auth->auth["uname"]."'".$qw." order by domain_from_req_uri, username_from_req_uri";
		$sd_res=$db->query($q);
		if (DB::isError($sd_res)) {log_errors($sd_res, $errors); break;}

	}
}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
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
	<td align="right"><label for="usrnm_from_uri">username from request uri:</label></td>
	<td><?$f->show_element("usrnm_from_uri");?></td>
	</tr>
	<tr>
	<td align="right"><label for="domain_from_uri">domain from request uri:</label></td>
	<td><?$f->show_element("domain_from_uri");?></td>
	</tr>
	<tr>
	<td align="right"><label for="new_uri">new request uri:</label></td>
	<td><?$f->show_element("new_uri");?></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","sip_address_completion(f.new_uri);");					// Finish form?>
</div>

<?if (!DB::isError($sd_res) and $sd_res->numRows()){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>request uri</th>
	<th>new request uri</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	while ($row=$sd_res->fetchRow(DB_FETCHMODE_OBJECT)){
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($row->username_from_req_uri."@".$row->domain_from_req_uri);?></td>
	<td align="left"><?echo nbsp_if_empty($row->new_request_uri);?></td>
	<td align="center"><a href="<?$sess->purl("speed_dial.php?kvrk=".uniqID("")."&edit_sd=".$row->username_from_req_uri."&edit_sd_dom=".$row->domain_from_req_uri);?>">edit</a></td>
	<td align="center"><a href="<?$sess->purl("speed_dial.php?kvrk=".uniqID("")."&dele_sd=".$row->username_from_req_uri."&dele_sd_dom=".$row->domain_from_req_uri);?>">delete</a></td>
	</tr>
	<?}//while
	$sd_res->free();?>
	</table>
<?}else{?>

<div class="swNumOfFoundRecords">No speed dials defined</div>

<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

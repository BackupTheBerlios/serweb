<?
/*
 * $Id: speed_dial.php,v 1.3 2004/03/09 16:08:45 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}

	if ($dele_sd){
		$q="delete from ".$config->table_speed_dial." where ".
			"username='".$auth->auth["uname"]."' and domain='".$config->realm."' and username_from_req_uri='".$dele_sd."' and domain_from_req_uri='".$dele_sd_dom."'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

        Header("Location: ".$sess->url("speed_dial.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	if ($edit_sd){
		$q="select username_from_req_uri, domain_from_req_uri, new_request_uri from ".$config->table_speed_dial.
			" where domain='".$config->realm."' and username='".$auth->auth["uname"]."' and username_from_req_uri='".$edit_sd."' and domain_from_req_uri='".$edit_sd_dom."'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		$row=mysql_fetch_object($res);
	}

	$f->add_element(array("type"=>"text",
	                             "name"=>"usrnm_from_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>$row->username_from_req_uri?$row->username_from_req_uri:"",
	                             "valid_regex"=>"^".$config->speed_dial_inititation,
	                             "valid_e"=>"username from request uri must start by: ".$config->speed_dial_inititation,
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"domain_from_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>$row->domain_from_req_uri?$row->domain_from_req_uri:$config->realm,
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"new_uri",
								 "size"=>16,
								 "maxlength"=>128,
	                             "value"=>$row->new_request_uri?$row->new_request_uri:"",
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


	if (isset($okey_x)){								// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			/* Process data */           // Data ok;

		if ($edit_sd) $q="update ".$config->table_speed_dial." set new_request_uri='$new_uri', username_from_req_uri='$usrnm_from_uri', domain_from_req_uri='$domain_from_uri' ".
			"where username_from_req_uri='$edit_sd' and domain='".$config->realm."' and username='".$auth->auth["uname"]."'";
		else $q="insert into ".$config->table_speed_dial." (username, domain, username_from_req_uri, domain_from_req_uri, new_request_uri) ".
			"values ('".$auth->auth["uname"]."', '".$config->realm."', '$usrnm_from_uri', '$domain_from_uri', '$new_uri')";

		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}


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
		$sd_res=MySQL_Query($q);
		if (!$sd_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

	}
}while (false);

if ($okey_x){							//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?echo $config->title;?></title>
<?print_html_head();?>
<script language="JavaScript">
<!--
	function sip_address_completion(adr){
		var default_domain='<?echo $config->default_domain;?>';

		var re = /^<?echo str_replace('/','\/',$reg->user);?>$/i;
		if (re.test(adr.value)) {
			adr.value=adr.value+'@'+default_domain;
		}

		var re = /^<?echo str_replace('/','\/',$reg->address);?>$/i
		var re2= /^sip:/i;
		if (re.test(adr.value) && !re2.test(adr.value)) {
			adr.value='sip:'+adr.value;
		}
	}
//-->
</script>
<script language="JavaScript" src="ctd.js"></script>
</head>
<?
	print_html_body_begin(12, true, true, get_user_name($errors));
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>

<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="right" class="f12b">username from request uri:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("usrnm_from_uri");?></td>
	</tr>
	<tr>
	<td align="right" class="f12b">domain from request uri:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("domain_from_uri");?></td>
	</tr>
	<tr>
	<td align="right" class="f12b">new request uri:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("new_uri");?></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","sip_address_completion(f.new_uri);");					// Finish form?>

<?if ($sd_res and MySQL_num_rows($sd_res)){?>

<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT">request uri</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT">new request uri</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT">&nbsp;</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT">&nbsp;</td>
	</tr>
	<tr><td colspan="7" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?$odd=0;
	while ($row=MySQL_Fetch_Object($sd_res)){
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'bgcolor="#FFFFFF"':'bgcolor="#EAF0F4"';?>>
	<td align="left" class="f12">&nbsp;<?echo $row->username_from_req_uri."@".$row->domain_from_req_uri;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12"><?echo $row->new_request_uri;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12"><a href="<?$sess->purl("speed_dial.php?kvrk=".uniqID("")."&edit_sd=".$row->username_from_req_uri."&edit_sd_dom=".$row->domain_from_req_uri);?>">edit</a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12"><a href="<?$sess->purl("speed_dial.php?kvrk=".uniqID("")."&dele_sd=".$row->username_from_req_uri."&dele_sd_dom=".$row->domain_from_req_uri);?>">delete</a></td>
	</tr>
	<?}?>
	</table>
</td></tr>
</table>
<?}else{?>

<br><div align="center">No speed dials defined</div>

<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

<?
require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                   // create a form object
$f2 = new form;                   // create a form object

define("FOREVER",567648000);	//number of second for forever (18 years)

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}

	$q="select email_address from ".$config->table_subscriber." where user_id='".$auth->auth["uname"]."'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	$row=mysql_fetch_object($res);

	$q="select user from ".$config->table_grp." where user='".$auth->auth["uname"]."' and grp='voicemail'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	$f2vm=MySQL_Num_Rows($res); //forward to voicemail ????
	
	$f->add_element(array("type"=>"text",
	                             "name"=>"email",
								 "size"=>16,
								 "maxlength"=>50,
	                             "valid_regex"=>$reg_validate_email,
	                             "valid_e"=>"not valid email address",
	                             "value"=>$row->email_address,
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"checkbox",
								 "checked"=>$f2vm,
	                             "value"=>1,
	                             "name"=>"f2voicemail"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"passwd",
	                             "value"=>"",
								 "size"=>16,
								 "maxlength"=>25,
								 "pass"=>1,
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"passwd_r",
	                             "value"=>"",
								 "size"=>16,
								 "maxlength"=>25,
								 "pass"=>1,
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_change.gif",
								 "extrahtml"=>"alt='change'"));
								 
	$f2->add_element(array("type"=>"text",
	                             "name"=>"sip_address",
								 "size"=>16,
								 "maxlength"=>128,
								 "minlength"=>1,
	                             "length_e"=>"you mmust fill sip address",
	                             "valid_regex"=>"^".$reg->sip_address."$",
	                             "valid_e"=>"not valid sip address",
								 "extrahtml"=>"onBlur='sip_address_completion(this)' style='width:120px;'"));

	$options = array(array("label"=>"one hour","value"=>3600),
					array("label"=>"one day","value"=>86400),
					array("label"=>"permanent","value"=>FOREVER));
	
	$f2->add_element(array("type"=>"select",
								"name"=>"expires",
								"options"=>$options,
								"size"=>1,
								"value"=>3600));
	
	$f2->add_element(array("type"=>"submit",
	                             "name"=>"okey2",
	                             "src"=>$config->img_src_path."butons/b_add.gif",
								 "extrahtml"=>"alt='add'"));
	
	if ($del_contact){
		/* construct FIFO command */
		$fifo_cmd=":ul_rm_contact:".$config->reply_fifo_filename."\n".
			$config->ul_table."\n".		//table
			$auth->auth["uname"]."\n".	//username
			$del_contact."\n";			//contact

		write2fifo($fifo_cmd, $errors);

		if ($errors) break;		
		
        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("")));
		page_close();
		exit;
	
	}

	if (isset($okey2_x)){								// Is there data to process?
		if ($err = $f2->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

		/* Process data */           // Data ok; 

		/* construct FIFO command */
		$fifo_cmd=":ul_add:".$config->reply_fifo_filename."\n".
			$config->ul_table."\n".			//table
			$auth->auth["uname"]."\n".		//username
			$sip_address."\n".				//contact
			$expires."\n".					//expires
			$config->ul_priority."\n";		//priority

		write2fifo($fifo_cmd, $errors);

		if ($errors) break;		
		
        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("")));
		page_close();
		exit;
	
	}

	if (isset($okey_x)){								// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

		if ($passwd and ($passwd != $passwd_r)){
			$errors[]="passwords not match"; break;
		}

			/* Process data */           // Data ok; 

		$qpass="";
		if ($passwd){
			
			$inp=$auth->auth["uname"].":".$config->realm.":".$passwd;
			$ha1=md5($inp);
		
			$inpb=$auth->auth["uname"]."@".$config->domainname.":".$config->realm.":".$passwd;
			$ha1b=md5($inpb);
			
			$qpass=", password='$passwd', ha1='$ha1', ha1b='$ha1b'";
		}
		
 		$q="update ".$config->table_subscriber." set email_address='$email', datetime_modified=now()".$qpass.
			" where user_id='".$auth->auth["uname"]."'";

		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		if ($f2vm xor $f2voicemail){  // change forward to voicemai state?
			if ($f2voicemail) $q="insert into ".$config->table_grp." (user, grp) values ('".$auth->auth["uname"]."', 'voicemail')";
			else $q="delete from ".$config->table_grp." where user='".$auth->auth["uname"]."' and grp='voicemail'";
			
			$res=MySQL_Query($q);
			if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		}
		
		
        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("")."&message=".RawURLencode("values changed successfully")));
		page_close();
		exit;
	}
}while (false);

do{
	if ($db){

		// get aliases
		$q="select user from ".$config->table_aliases." where lower(contact)=lower('sip:".$auth->auth["uname"]."@".$config->default_domain."') order by user";
		$aliases_res=MySQL_Query($q);
		if (!$aliases_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		
		// get Access-Control-list
		$q="select grp from ".$config->table_grp." where user='".$auth->auth["uname"]."' order by grp";
		$grp_res=MySQL_Query($q);
		if (!$grp_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		// get UsrLoc
		$q="select contact, expires, q, callid, cseq from ".$config->table_location." where user='".$auth->auth["uname"]."' order by contact";
		$location_res=MySQL_Query($q);
		if (!$location_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	
	}
						 
}while (false);

if ($okey_x){							//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>iptel.org, the IP Telephony Site</title>
<?print_html_head();?>
<script language="JavaScript">
<!--
	function sip_address_completion(adr){
		var default_domain='<?echo $config->default_domain;?>';
		
		var re = /^<?echo str_replace('/','\/',$reg->user);?>$/i;
//		var re = new RegExp("^<?echo $reg->user;?>$","i");
		if (re.test(adr.value)) {
			adr.value=adr.value+'@'+default_domain;
		}

		var re = /^<?echo str_replace('/','\/',$reg->address);?>$/i
//		var re = new RegExp("^<?echo $reg->address;?>$","i");
		var re2= /^sip:/i;
		if (re.test(adr.value) && !re2.test(adr.value)) {
			adr.value='sip:'+adr.value;
		}
	}
//-->
</script>
</head>
<?
	print_html_body_begin(1, true, true);
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>

<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="right" class="f12b">your email:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("email");?></td>
	</tr>
	<tr>
	<td align="right" class="f12b">forwarding to voicemail:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("f2voicemail");?></td>
	</tr>
	<tr>
	<td align="right" class="f12b">your password:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("passwd");?></td>
	</tr>
	<tr>
	<td align="right" class="f12b">retype password:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("passwd_r");?></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("
	if (f.passwd.value!=f.passwd_r.value){
		alert('passwords not match');
		f.passwd.focus();
		return (false);
	}
");					// Finish form?>
<!-- </td></tr>
</table>
 --><br clear="all"><br>

<table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
<tr valign="top"><td width="50%">
<?if ($aliases_res and MySQL_num_rows($aliases_res)){?>
	<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
	<tr><td>
		<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
		<tr><td class="titleT">your aliases:</td></tr>
		<tr><td height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
		<?while ($row=MySQL_Fetch_Object($aliases_res)){?>
		<tr><td align="center" class="f12"><?echo $row->user;?></td></tr>
		<?}?>
		</table>
	</td></tr>
	</table>
<?}?>
&nbsp;
</td><td width="50%">
<?if ($grp_res and MySQL_num_rows($grp_res)){?>
	<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
	<tr><td>
		<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
		<tr><td class="titleT">Access-Control-list:</td></tr>
		<tr><td height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
		<?while ($row=MySQL_Fetch_Object($grp_res)){?>
		<tr><td align="center" class="f12"><?echo $row->grp;?></td></tr>
		<?}?>
		</table>
	</td></tr>
	</table>
<?}?>
&nbsp;
</td></tr>
</table>

<?if ($location_res and MySQL_num_rows($location_res)){?>

<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT" width="125">contact</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="125">expires</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="60">priority</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="125">call id</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="63">&nbsp;</td>
	</tr>
	<tr><td colspan="9" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?while ($row=MySQL_Fetch_Object($location_res)){
		if (Substr($row->expires,0,10)==date('Y-m-d')) $date=Substr($row->expires,11,5);
		else $date=Substr($row->expires,0,10);
	?>
	<tr valign="top">
	<td align="center" class="f12" width="125"><?echo $row->contact;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="125"><?echo $date;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="60"><?echo $row->q;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="125"><?echo $row->callid;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="63"><a href="<?$sess->purl("my_account?kvrk=".uniqid('')."&del_contact=".rawURLEncode($row->contact));?>">delete</a></td>
	</tr>
	<?}?>
	</table>
</td></tr>
</table>

<?}?>
<br><br>
<?$f2->start("form2");				// Start displaying form?>
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td>
	<table border="0" cellspacing="0" cellpadding="0" align="left">
	<tr><td class="title" width="506">add new contact:</td></tr>
	</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
	<table border="0" cellspacing="0" cellpadding="0" align="left">
	<tr><td class="f12b">sip address:</td><td>&nbsp;&nbsp;</td><td><?$f2->show_element("sip_address");?></td><td>&nbsp;&nbsp;</td>
	<td class="f12b">expires:</td><td>&nbsp;&nbsp;</td><td><?$f2->show_element("expires");?></td><td>&nbsp;&nbsp;</td>
	<td><?$f2->show_element("okey2");?></td></tr>
	</table>
</td></tr>
</table>
<?$f2->finish();					// Finish form?>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td width="50%" align="center"><a href="javascript:alert('not implemented yet!');"><img src="<?echo $config->img_src_path;?>butons/b_dial_your_voicemail.gif" width="165" height="16" border="0"></a></td>
<td width="50%" align="center"><a href="javascript:alert('not implemented yet!');"><img src="<?echo $config->img_src_path;?>butons/b_test_firewall_NAT.gif" width="165" height="16" border="0"></a></td>
</tr>
</table>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

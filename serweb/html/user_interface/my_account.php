<?
/*
 * $Id: my_account.php,v 1.8 2002/09/20 20:02:33 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));

if ($perm->have_perm("admin")){
	if ($uid) $user_id=$uid;
	else $user_id=$auth->auth["uname"];
}
else $user_id=$auth->auth["uname"];
				 
$reg = new Creg;				// create regular expressions class
$f = new form;                   // create a form object
$f2 = new form;                   // create a form object

class Cusrloc {
	var $uri, $q, $expires;
	
	function Cusrloc ($uri, $q, $expires){
		$this->uri=$uri;
		$this->q=$q;
		$this->expires=$expires;
	}
}

define("FOREVER",567648000);	//number of second for forever (18 years)

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}

	$q="select email_address, allow_find from ".$config->table_subscriber." where user_id='".$user_id."'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	$row=mysql_fetch_object($res);

	$q="select user from ".$config->table_grp." where user='".$user_id."' and grp='voicemail'";
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
	$f->add_element(array("type"=>"checkbox",
								 "checked"=>$row->allow_find,
	                             "value"=>1,
	                             "name"=>"allow_find"));
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
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"uid",
	                             "value"=>$uid));
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
	
	$f2->add_element(array("type"=>"hidden",
	                             "name"=>"uid",
	                             "value"=>$uid));

	$f2->add_element(array("type"=>"submit",
	                             "name"=>"okey2",
	                             "src"=>$config->img_src_path."butons/b_add.gif",
								 "extrahtml"=>"alt='add'"));
	
	if ($del_contact){
		/* construct FIFO command */
		$fifo_cmd=":ul_rm_contact:".$config->reply_fifo_filename."\n".
			$config->ul_table."\n".		//table
			$user_id."\n".	//username
			$del_contact."\n\n";			//contact

		$message=write2fifo($fifo_cmd, $errors, $status);
		if ($errors) break;
		if (substr($status,0,3)!="200") {$errors[]=$status; break; }
		
        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("")."&uid=".RawURLEncode($uid)."&message=".RawURLEncode($message)));
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
			$user_id."\n".		//username
			$sip_address."\n".				//contact
			$expires."\n".					//expires
			$config->ul_priority."\n\n";		//priority

		$message=write2fifo($fifo_cmd, $errors, $status);
		if ($errors) break;
		if (substr($status,0,3)!="200") {$errors[]=$status; break; }
		
        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("")."&uid=".RawURLEncode($uid)."&message=".RawURLEncode($message)));
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
			
			$inp=$user_id.":".$config->realm.":".$passwd;
			$ha1=md5($inp);
		
			$inpb=$user_id."@".$config->domainname.":".$config->realm.":".$passwd;
			$ha1b=md5($inpb);
			
			$qpass=", password='$passwd', ha1='$ha1', ha1b='$ha1b'";
		}
		
 		$q="update ".$config->table_subscriber." set email_address='$email', allow_find='".($allow_find?1:0)."', datetime_modified=now()".$qpass.
			" where user_id='".$user_id."'";

		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		if ($f2vm xor $f2voicemail){  // change forward to voicemai state?
			if ($f2voicemail) $q="insert into ".$config->table_grp." (user, grp) values ('".$user_id."', 'voicemail')";
			else $q="delete from ".$config->table_grp." where user='".$user_id."' and grp='voicemail'";
			
			$res=MySQL_Query($q);
			if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		}
		
		
        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("")."&message=".RawURLencode("values changed successfully")."&uid=".RawURLEncode($uid)));
		page_close();
		exit;
	}
}while (false);

do{
	if ($db){

		// get aliases
		$q="select user from ".$config->table_aliases." where lower(contact)=lower('sip:".$user_id."@".$config->default_domain."') order by user";
		$aliases_res=MySQL_Query($q);
		if (!$aliases_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		
		// get Access-Control-list
		if (!$config->show_voicemail_acl) $qc=" and grp!='voicemail' ";
		else $qc="";
		$q="select grp from ".$config->table_grp." where user='".$user_id."'".$qc." order by grp";
		$grp_res=MySQL_Query($q);
		if (!$grp_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		// get UsrLoc
		$fifo_cmd=":ul_show_contact:".$config->reply_fifo_filename."\n".
			$config->ul_table."\n".		//table
			$user_id."\n\n";	//username

		$out=write2fifo($fifo_cmd, $errors, $status);
		if ($errors or !$out) break;		
		if (substr($status,0,3)!="200" and substr($status,0,3)!="404") {$errors[]=$status; break; }

		$out_arr=explode("\n", $out);
		
		foreach($out_arr as $val){
			if (!ereg("^[[:space:]]*$", $val)){
				if (ereg("<([^>]*)>;q=([0-9.]*);expires=([0-9]*)", $val, $regs))
					$usrloc[]=new Cusrloc($regs[1], $regs[2], $regs[3]);
				else { $errors[]="sorry error -- invalid output from fifo"; break; }
			}
		}
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
</head>
<?
	if ($perm->have_perm("admin") and $uid){
		print_html_body_begin(false, true, true);
		echo "user: ".$uid."<br>";
	}
	else print_html_body_begin(1, true, true, get_user_name($errors));
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
<?if ($config->show_voicemail_acl){?>
	<tr>
	<td align="right" class="f12b">forwarding to voicemail:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("f2voicemail");?></td>
	</tr>
<?}?>	
	<tr>
	<td align="right" class="f12b">allow find me by other users:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("allow_find");?></td>
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
		<tr><td align="center" class="f12"><?echo $row->user;?>&nbsp;</td></tr>
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
		<tr><td align="center" class="f12"><?echo $row->grp;?>&nbsp;</td></tr>
		<?}?>
		</table>
	</td></tr>
	</table>
<?}?>
&nbsp;
</td></tr>
</table>

<?if (is_array($usrloc)){?>

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
	<td class="titleT" width="63">&nbsp;</td>
	</tr>
	<tr><td colspan="7" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?foreach($usrloc as $row){
		$expires=date('Y-m-d H:i',time()+$row->expires);
		
		if (Substr($expires,0,10)==date('Y-m-d')) $date=Substr($expires,11,5);
		else $date=Substr($expires,0,10);
	?>
	<tr valign="top">
	<td align="left" class="f12" width="125">&nbsp;<?echo $row->uri;?>&nbsp;</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="125"><?echo $date;?>&nbsp;</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="60"><?echo $row->q;?>&nbsp;</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="63"><a href="<?$sess->purl("my_account.php?kvrk=".uniqid('')."&uid=".rawURLEncode($uid)."&del_contact=".rawURLEncode($row->uri));?>">delete</a></td>
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
<td width="50%" align="center"><?if ($config->enable_dial_voicemail){?><a href="javascript:alert('not implemented yet!');"><img src="<?echo $config->img_src_path;?>butons/b_dial_your_voicemail.gif" width="165" height="16" border="0"></a><?} else echo "&nbsp;";?></td>
<td width="50%" align="center"><?if ($config->enable_test_firewall){?><a href="javascript:alert('not implemented yet!');"><img src="<?echo $config->img_src_path;?>butons/b_test_firewall_NAT.gif" width="165" height="16" border="0"></a><?} else echo "&nbsp;";?></td>
</tr>
</table>

<br>
<? if ($perm->have_perm("admin") and $uid){?>
	<a href="<?$sess->purl("../admin/users.php?kvrk=".uniqid(""));?>">back to main page</a><br>
<?}?>
<?print_html_body_end();?>
</html>
<?page_close();?>

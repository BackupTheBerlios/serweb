<?
/*
 * $Id: my_account.php,v 1.32 2004/03/25 21:13:33 kozlik Exp $
 */

require "prepend.php";

put_headers();

//this line is useful when new session is created when user forgot password
//we must ensure to value in $HTTP_COOKIE_VARS["phplib_Session"] and $HTTP_GET_VARS["phplib_Session"] is same
if (isset($HTTP_GET_VARS["phplib_Session"]) and isset($HTTP_COOKIE_VARS["phplib_Session"])) $HTTP_COOKIE_VARS["phplib_Session"]=$HTTP_GET_VARS["phplib_Session"];

page_open (array("sess" => "phplib_Session_Pre_Auth",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));

if (isset($_POST["uid"])) $uid=$_POST["uid"];
elseif (isset($_GET["uid"])) $uid=$_GET["uid"];
else $uid=null;
				 
if ($perm->have_perm("admin")){
	if ($uid) $user_id=$uid;
	else $user_id=$auth->auth["uname"];
}
else $user_id=$auth->auth["uname"];

$reg = new Creg;				// create regular expressions class
$f = new form;                   // create a form object
$f2 = new form;                   // create a form object

class Cusrloc {
	var $uri, $q, $expires, $geo_loc;

	function Cusrloc ($uri, $q, $expires, $geo_loc){
		$this->uri=$uri;
		$this->q=$q;
		$this->expires=$expires;
		$this->geo_loc=$geo_loc;
	}
}

define("FOREVER",567648000);	//number of second for forever (18 years)

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="cannot connect to sql server"; break;}

	$q="select email_address, allow_find, timezone from ".$config->table_subscriber.
		" where username='".$user_id."' and domain='".$config->realm."'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query (1), line: ".__LINE__; break;}
	$row=mysql_fetch_object($res);

	set_timezone($errors);

	$options=array();
	$opt=get_time_zones($errors);
	foreach ($opt as $v) $options[]=array("label"=>$v,"value"=>$v);

	$f->add_element(array("type"=>"text",
	                             "name"=>"email",
								 "size"=>16,
								 "maxlength"=>50,
	                             "valid_regex"=>$reg_validate_email,
	                             "valid_e"=>"not valid email address",
	                             "value"=>$row->email_address,
								 "extrahtml"=>"style='width:200px;'"));
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
								 "extrahtml"=>"style='width:200px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"passwd_r",
	                             "value"=>"",
								 "size"=>16,
								 "maxlength"=>25,
								 "pass"=>1,
								 "extrahtml"=>"style='width:200px;'"));
	$f->add_element(array("type"=>"select",
								 "name"=>"timezone",
								 "options"=>$options,
								 "size"=>1,
	                             "value"=>$row->timezone,
								 "extrahtml"=>"style='width:200px;'"));
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

	if (isset($_GET['del_contact'])){
		/* construct FIFO command */
		/*
		if ($config->ul_multidomain)
			$ul_name=$user_id."@".$config->default_domain."\n";
		else
			$ul_name=$user_id."\n";
		*/
		$ul_name=$user_id."@".$config->default_domain."\n";
		$fifo_cmd=":ul_rm_contact:".$config->reply_fifo_filename."\n".
			$config->ul_table."\n".		//table
			$ul_name.
			$del_contact."\n\n";			//contact

		$message=write2fifo($fifo_cmd, $errors, $status);
		if ($errors) break;
		/* we accept any 2xx as ok */
		if (substr($status,0,1)!="2") {$errors[]=$status; break; }

        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("")."&uid=".RawURLEncode($uid)."&message=".RawURLEncode($status)));
		page_close();
		exit;

	}

	if (isset($okey2_x)){								// Is there data to process?
		if ($err = $f2->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

		if (is_array($config->denny_reg) and !$perm->have_perm("admin")){
			foreach ($config->denny_reg as $val){
				if (Ereg($val->reg, $sip_address)) {$errors[]=$val->label; break;}
			}
			if ($errors) break;
		}

		/* Process data */           // Data ok;

		if ($config->ul_replication) $replication="0\n";
		else $replication="";

		/* construct FIFO command */
		/*
		if ($config->ul_multidomain)
			$ul_name=$user_id."@".$config->default_domain."\n";
		else
			$ul_name=$user_id."\n";
		*/
		$ul_name=$user_id."@".$config->default_domain."\n";
		$fifo_cmd=":ul_add:".$config->reply_fifo_filename."\n".
			$config->ul_table."\n".			//table
			$ul_name.
			$sip_address."\n".				//contact
			$expires."\n".					//expires
			$config->ul_priority."\n".	// priority
			$replication."\n";

		$message=write2fifo($fifo_cmd, $errors, $status);
		if ($errors) break;
		if (substr($status,0,1)!="2") {$errors[]=$status; break; }

        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("")."&uid=".RawURLEncode($uid)."&message=".RawURLEncode($status)));
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

 		$q="update ".$config->table_subscriber.
			" set email_address='$email', allow_find='".($allow_find?1:0)."', timezone='$timezone', datetime_modified=now()".$qpass.
			" where username='".$user_id."' and domain='".$config->realm."'";

		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query(3), line: ".__LINE__; break;}

        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("")."&message=".RawURLencode("values changed successfully")."&uid=".RawURLEncode($uid)));
		page_close();
		exit;
	}
}while (false);

do{
	if ($db){

		// get aliases
		$q="select username from ".$config->table_aliases.
			" where lower(contact)=lower('sip:".$user_id."@".$config->default_domain."') order by username";
		$aliases_res=MySQL_Query($q);
		if (!$aliases_res) {$errors[]="error in SQL query(5), line: ".__LINE__; break;}

		// get Access-Control-list
		$q="select grp from ".$config->table_grp." where domain='".$config->realm.
			"' and username='".$user_id."' order by grp";
		$grp_res=MySQL_Query($q);
		if (!$grp_res) {$errors[]="error in SQL query(6), line: ".__LINE__; break;}

		// get UsrLoc
		/*
		if ($config->ul_multidomain)
			$ul_name=$user_id."@".$config->default_domain."\n";
		else
			$ul_name=$user_id."\n";
		*/
		$ul_name=$user_id."@".$config->default_domain."\n";
		$fifo_cmd=":ul_show_contact:".$config->reply_fifo_filename."\n".
		$config->ul_table."\n".		//table
		$ul_name."\n";	//username

		$out=write2fifo($fifo_cmd, $err, $status);
		if ($err or !$out) {
			$errors=array_merge($errors, $err); // No!
			break;
		}
		if (!$out) break;

		if (substr($status,0,1)!="2" and substr($status,0,3)!="404") {$errors[]=$status; break; }

		$out_arr=explode("\n", $out);

		foreach($out_arr as $val){
			if (!ereg("^[[:space:]]*$", $val)){
				if (ereg("<([^>]*)>;q=([0-9.]*);expires=([0-9]*)", $val, $regs))
					$usrloc[]=new Cusrloc($regs[1], $regs[2], $regs[3], get_location($regs[1], $errors));
				else { $errors[]="sorry error -- invalid output from fifo"; break; }
			}
		}

	}

}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript">
<!--
	var stun_win=null;

	function stun_applet_win(){
		if (stun_win != null) stun_win.close();
			stun_win=window.open("stun_applet.php","stun_win","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,top=20,left=20,width=<? echo $config->stun_applet_width; ?>,height=<? echo $config->stun_applet_height; ?>");
			stun_win.window.focus();
			return;
	}
//-->
</script>
<script language="JavaScript" src="<?echo $config->js_src_path;?>sip_address_completion.js.php"></script>
<script language="JavaScript" src="<?echo $config->js_src_path;?>click_to_dial.js.php"></script>
<?
if ($perm->have_perm("admin") and $uid){

	/* script is called from admin interface, load page attributes of admin interface */
	require ("../admin/page_attributes.php");
	$page_attributes['selected_tab']="users.php";

	print_html_body_begin($page_attributes);
	echo "<div class=\"swNameOfUser\">user: ".$uid."</div>";
}
else {
	$page_attributes['user_name']=get_user_name($errors);
	print_html_body_begin($page_attributes);
}
?>

<div class="swForm">
<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="email">your email:</label></td>
	<td><?$f->show_element("email");?></td>
	</tr>
	<tr>
	<td><label for="allow_find">allow find me by other users:</label></td>
	<td><?$f->show_element("allow_find");?></td>
	</tr>
	<tr>
	<td><label for="timezone">your timezone:</label></td>
	<td><?$f->show_element("timezone");?></td>
	</tr>
	<tr>
	<td><label for="passwd">your password:</label></td>
	<td><?$f->show_element("passwd");?></td>
	</tr>
	<tr>
	<td><label for="passwd_r">retype password:</label></td>
	<td><?$f->show_element("passwd_r");?></td>
	</tr>
	<tr>
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
</div>


<?if ($aliases_res and MySQL_num_rows($aliases_res)){?>
	<div id="swMAAliasesTable">
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr><th>your aliases:</th></tr>
	<?while ($row=MySQL_Fetch_Object($aliases_res)){?>
	<tr><td align="center"><?echo nbsp_if_empty($row->username);?></td></tr>
	<?}?>
	</table>
	</div>
<?}?>

<?if ($grp_res and MySQL_num_rows($grp_res)){?>
	<div id="swMAACLTable">
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr><th>Access-Control-list:</td></tr>
	<?while ($row=MySQL_Fetch_Object($grp_res)){?>
	<tr><td align="center"><?echo nbsp_if_empty($row->grp);?></td></tr>
	<?}?>
	</table>
	</div>
<?}?>

<br class="swCleaner"><br>

<?if (isset($usrloc) and is_array($usrloc)){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>contact</th>
	<th>expires</th>
	<th>priority</th>
	<th>location</th>
	<th>&nbsp;</th>
	</tr>
	<?foreach($usrloc as $row){
		$expires=date('Y-m-d H:i',time()+$row->expires);

		if (Substr($expires,0,10)==date('Y-m-d')) $date=Substr($expires,11,5);
		else $date=Substr($expires,0,10);
	?>
	<tr valign="top">
	<td align="left"><?echo nbsp_if_empty($row->uri);?></td>
	<td align="center"><?echo nbsp_if_empty($date);?></td>
	<td align="center"><?echo nbsp_if_empty($row->q);?></td>
	<td align="center"><?echo nbsp_if_empty($row->geo_loc);?></td>
	<td align="center"><a href="<?$sess->purl("my_account.php?kvrk=".uniqid('')."&uid=".rawURLEncode($uid)."&del_contact=".rawURLEncode($row->uri));?>">delete</a></td>
	</tr>
	<?}?>
	</table>
<?}?>

<h2 class="swTitle">add new contact:</h2>

<div class="swForm">
<?$f2->start("form2");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="sip_address">sip address:</label></td>
	<td><?$f2->show_element("sip_address");?></td>
	<td><label for="expires">expires:</label></td>
	<td><?$f2->show_element("expires");?></td>
	<td><?$f2->show_element("okey2");?></td></tr>
	</table>
<?$f2->finish();					// Finish form?>
</div>



<?if ($config->enable_dial_voicemail or $config->enable_test_firewall){?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<?if ($config->enable_dial_voicemail){?><td align="center"><a href="javascript: open_ctd_win('<?echo RawURLEncode("sip:".$user_id."@".$config->default_domain); ?>');"><img src="<?echo $config->img_src_path;?>butons/b_dial_your_voicemail.gif" width="165" height="16" border="0"></a></td><?}?>
<?if ($config->enable_test_firewall){?><td align="center"><a href="javascript:stun_applet_win();"><img src="<?echo $config->img_src_path;?>butons/b_test_firewall_NAT.gif" width="165" height="16" border="0"></a></td><?}?>
</tr>
</table>
<?}?>

<? if ($perm->have_perm("admin") and $uid){?>
	<div class="swBackToMainPage"><a href="<?$sess->purl($config->admin_pages_path."users.php?kvrk=".uniqid(""));?>" class="f14">back to main page</a></div>
<?}?>
<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

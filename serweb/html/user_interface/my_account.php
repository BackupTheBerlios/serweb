<?
/*
 * $Id: my_account.php,v 1.34 2004/04/14 20:51:31 kozlik Exp $
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


define("FOREVER",567648000);	//number of second for forever (18 years)

do{
	if (!$data = CData_Layer::create($errors)) break;

	$data->set_timezone($errors);
	
	if (false === $row = $data->get_sip_user_details($user_id, $config->domain, $errors)) break;
	
	$options=array();
	$opt=$data->get_time_zones($errors);
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

		if (false === $status = $data->del_contact($user_id, $config->domain, $_GET['del_contact'], $errors)) break;

        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("")."&uid=".RawURLEncode($uid)."&message=".RawURLEncode($status)));
		page_close();
		exit;
	}

	if (isset($okey2_x)){						// Is there data to process?
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

		if (false === $status = $data->add_contact($user_id, $config->domain, $_POST['sip_address'], $_POST['expires'], $errors)) break;

        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("")."&uid=".RawURLEncode($uid)."&message=".RawURLEncode($status)));
		page_close();
		exit;

	}

	if (isset($okey_x)){						// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

		if ($_POST['passwd'] and ($_POST['passwd'] != $_POST['passwd_r'])){
			$errors[]="passwords don't match"; break;
		}

			/* Process data */           // Data ok;

		$pass=NULL;
		if ($_POST['passwd']) $pass=$_POST['passwd'];

		if (!$data->update_sip_user_details($user_id, $config->domain, $pass, $_POST['email'], isset($_POST['allow_find'])?1:0, $_POST['timezone'], $errors)) break;

        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("")."&message=".RawURLencode("values changed successfully")."&uid=".RawURLEncode($uid)));
		page_close();
		exit;
	}
}while (false);

do{
	$aliases_res = $acl_res = $usrloc = array();
	if ($data){

		// get aliases
		if (false === $aliases_res = $data->get_aliases("sip:".$user_id."@".$config->domain, $errors)) break;

		// get Access-Control-list
		if (false === $acl_res = $data->get_acl($user_id, $config->domain, $errors)) break;

		// get UsrLoc
		if (false === $usrloc = $data->get_usrloc($user_id, $config->domain, $errors)) break;

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
		$page_attributes['user_name']=$data->get_user_name($errors);
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


<?if (is_array($aliases_res) and count($aliases_res)){?>
	<div id="swMAAliasesTable">
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr><th>your aliases:</th></tr>
	<?foreach($aliases_res as $row){?>
	<tr><td align="center"><?echo nbsp_if_empty($row->username);?></td></tr>
	<?}//while?>
	</table>
	</div>
<?}?>

<?if (is_array($acl_res) and count($acl_res)){?>
	<div id="swMAACLTable">
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr><th>Access-Control-list:</td></tr>
	<?foreach($acl_res as $row){?>
	<tr><td align="center"><?echo nbsp_if_empty($row->grp);?></td></tr>
	<?}//while?>
	</table>
	</div>
<?}?>

<br class="swCleaner"><br>

<?if (is_array($usrloc) and count($usrloc)){?>

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

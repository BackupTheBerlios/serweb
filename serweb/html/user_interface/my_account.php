<?
/*
 * $Id: my_account.php,v 1.35 2004/08/09 12:21:27 kozlik Exp $
 */

$_data_layer_required_methods=array('get_sip_user_details', 'update_sip_user_details', 'del_contact',
		'add_contact', 'get_usrloc', 'get_acl_of_user', 'get_forward_to_voicemail_grp', 'update_forward_to_voicemail_grp',
		'check_admin_perms_to_user', 'set_timezone', 'get_time_zones', 'set_password_to_user', 'get_aliases', 
		'get_user_real_name');

require "prepend.php";

put_headers();

//this line is useful when new session is created when user forgot password
//we must ensure to value in $HTTP_COOKIE_VARS["phplib_Session"] and $HTTP_GET_VARS["phplib_Session"] is same
if (isset($HTTP_GET_VARS["phplib_Session"]) and isset($HTTP_COOKIE_VARS["phplib_Session"])) $HTTP_COOKIE_VARS["phplib_Session"]=$HTTP_GET_VARS["phplib_Session"];

page_open (array("sess" => "phplib_Session_Pre_Auth",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));

$reg = new Creg;				// create regular expressions class
$f = new form;                   // create a form object
$f2 = new form;                   // create a form object


define("FOREVER",567648000);	//number of second for forever (18 years)


$uid=null; //contains Cserweb_auth if admin editing this user

do{
	// get $user_id of user which accout should be displayed
	if ($perm->have_perm("admin")){
		if (false === $uid = get_userauth_from_get_param('u')) {
			$user_id=$serweb_auth;
		}
		else {
			if (0 > ($pp=$data->check_admin_perms_to_user($serweb_auth, $uid, $errors))) break;
			if (!$pp){
				die("You can't manage user '".$uid->uname."' this user is from different domain");
				break;
			}
	
			$user_id=$uid;
		}
	}
	else $user_id=$serweb_auth;

	

	$data->set_timezone($user_id, $errors);

	if (false === $row = $data->get_sip_user_details($user_id, $errors)) break;

	if ($config->forwarding_to_voicemail_by_group){
		if (($f2vm=$data->get_forward_to_voicemail_grp($user_id, $errors)) < 0) break;

		$f->add_element(array("type"=>"checkbox",
									 "checked"=>$f2vm,
		                             "value"=>1,
		                             "name"=>"f2voicemail"));
	}
	
	$options=array();
	$opt=$data->get_time_zones($errors);
	foreach ($opt as $v) $options[]=array("label"=>$v,"value"=>$v);

	if ($config->allow_change_email){
		$f->add_element(array("type"=>"text",
		                             "name"=>"email",
									 "size"=>16,
									 "maxlength"=>50,
		                             "valid_regex"=>$reg_validate_email,
		                             "valid_e"=>"not valid email address",
		                             "value"=>$row->email_address,
									 "extrahtml"=>"style='width:200px;'"));
	}

	if ($config->allow_change_status_visibility){
		$f->add_element(array("type"=>"checkbox",
									 "checked"=>$row->allow_show_status,
		                             "value"=>1,
		                             "name"=>"status_visibility"));
	}

	$f->add_element(array("type"=>"checkbox",
								 "checked"=>$row->allow_find,
	                             "value"=>1,
	                             "name"=>"allow_find"));
	if ($config->allow_change_password){
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
	}
	$f->add_element(array("type"=>"select",
								 "name"=>"timezone",
								 "options"=>$options,
								 "size"=>1,
	                             "value"=>$row->timezone,
								 "extrahtml"=>"style='width:200px;'"));

	if ($uid) userauth_to_form($uid, 'u', $f);
	
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_change.gif",
								 "extrahtml"=>"alt='change'"));

	if ($config->enable_usrloc){
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
	
		if ($uid) userauth_to_form($uid, 'u', $f2);
	
		$f2->add_element(array("type"=>"submit",
		                             "name"=>"okey2",
		                             "src"=>$config->img_src_path."butons/b_add.gif",
									 "extrahtml"=>"alt='add'"));
	}

	if ($config->enable_usrloc and isset($_GET['del_contact'])){

		if (false === $status = $data->del_contact($user_id->uname, $user_id->domain, $_GET['del_contact'], $errors)) break;

        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("").
		                                             "&m_contact_deleted=".RawURLEncode($status).
													 ($uid?("&".userauth_to_get_param($uid, 'u')):"")));
		page_close();
		exit;
	}

	if ($config->enable_usrloc and isset($okey2_x)){						// Is there data to process?
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

		if (false === $status = $data->add_contact($user_id->uname, $user_id->domain, $_POST['sip_address'], $_POST['expires'], $errors)) break;

        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("").
		                                             "&m_contact_added=".RawURLEncode($status).
													 ($uid?("&".userauth_to_get_param($uid, 'u')):"")));


		page_close();
		exit;

	}

	if (isset($okey_x)){						// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

		$pass=NULL;
		$email=NULL;
		$status_visibility=NULL;

		if ($config->allow_change_password){
			if ($_POST['passwd'] and ($_POST['passwd'] != $_POST['passwd_r'])){
				$errors[]="passwords don't match"; break;
			}

			if ($_POST['passwd']) $pass=$_POST['passwd'];
		}

		if ($config->allow_change_email) $email=$_POST['email'];

		if ($config->allow_change_status_visibility){
			if (isset($_POST['status_visibility']) and $_POST['status_visibility'])	$status_visibility='1';
			else $status_visibility='0';
		}

			/* Process data */           // Data ok;
		
		if (false === $data->update_sip_user_details($user_id, $email, isset($_POST['allow_find'])?1:0, $_POST['timezone'], $status_visibility, $errors)) break;

		//if password should be changed
		if (!is_null($pass)){
			if (false === $data->set_password_to_user($user_id, $pass, $errors)) break;
		}
		
		if ($config->forwarding_to_voicemail_by_group){
			if (isset($_POST['f2voicemail']) and $_POST['f2voicemail']) $f2voicemail=true;
			else $f2voicemail=false;
			
			if ($f2vm xor $f2voicemail){  // was changed forward to voicemail state?
				if ($f2voicemail) {
					if (false === $data->update_forward_to_voicemail_grp($user_id, 'add', $errors)) break;
				}
				else {
					if (false === $data->update_forward_to_voicemail_grp($user_id, 'del', $errors)) break;
				}
			}
		}
		
        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("").
		                                             "&m_changes_saved=1".
													 ($uid?("&".userauth_to_get_param($uid, 'u')):"")));
		page_close();
		exit;
	}
}while (false);

do{
	$aliases_res = $acl_res = $usrloc = array();
	if ($data){

		// get aliases
		if (false === $aliases_res = $data->get_aliases($user_id, $errors)) break;

		// get Access-Control-list
		if (false === $acl_res = $data->get_acl_of_user($user_id, $errors)) break;

		// get UsrLoc
		if ($config->enable_usrloc){
			if (false === $usrloc = $data->get_usrloc($user_id->uname, $user_id->domain, $errors)) break;
		}

	}

}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

if (isset($_GET['m_changes_saved'])){
	$message['short']="Changes saved";
	$message['long']="Your changes have been saved.";
}

if (isset($_GET['m_contact_deleted'])){
	$message['short']="Contact deleted";
	$message['long']="Your contact have been deleted. Returned status: ".$_GET['m_contact_deleted'];
}

if (isset($_GET['m_contact_added'])){
	$message['short']="Contact added";
	$message['long']="Your contact have been added. Returned status: ".$_GET['m_contact_added'];
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
$come_from_admin_interface = ($perm->have_perm("admin") and $uid);

if ($come_from_admin_interface){

	/* script is called from admin interface, load page attributes of admin interface */
	require ("../admin/page_attributes.php");
	$page_attributes['selected_tab']="users.php";

	print_html_body_begin($page_attributes);
	echo "<div class=\"swNameOfUser\">user: ".$uid->uname."</div>";
}
else {
	$page_attributes['user_name']=$data->get_user_real_name($user_id, $errors);
	print_html_body_begin($page_attributes);
}

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

//create copy of some options from config in order to sensitive options will not accessible via templates
$cfg=new stdclass();
$cfg->allow_change_email               = $config->allow_change_email;
$cfg->forwarding_to_voicemail_by_group = $config->forwarding_to_voicemail_by_group;
$cfg->allow_change_password            = $config->allow_change_password;
$cfg->enable_dial_voicemail            = $config->enable_dial_voicemail; 
$cfg->enable_test_firewall             = $config->enable_test_firewall;
$cfg->img_src_path                     = $config->img_src_path;
$cfg->enable_usrloc                    = $config->enable_usrloc;

if (!$aliases_res)	$aliases_res = array();
if (!$acl_res) 		$acl_res = array();
if (!$usrloc) 		$usrloc = array();

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign_by_ref("config", $cfg);		
$smarty->assign_by_ref("come_from_admin_interface", $come_from_admin_interface);		

$smarty->assign_by_ref("aliases_res", $aliases_res);
$smarty->assign_by_ref("acl_res", $acl_res);
$smarty->assign_by_ref("usrloc", $usrloc);

$smarty->assign('url_ctd', "javascript: open_ctd_win('".RawURLEncode("sip:".$user_id->uname."@".$user_id->domain)."');");
$smarty->assign('url_stun', "javascript:stun_applet_win();");
$smarty->assign('url_admin', $sess->url($config->admin_pages_path."users.php?kvrk=".uniqid("")));

$smarty->assign('sip_user', $user_id);

$smarty->assign_phplib_form('form', $f, 
		array('jvs_name'=>'form'), 
		array("after"=>$config->allow_change_password?"
			if (f.passwd.value!=f.passwd_r.value){
				alert('passwords not match');
				f.passwd.focus();
				return (false);
			}":""));

if ($config->enable_usrloc){
	$smarty->assign_phplib_form('form2', $f2, 
			array('jvs_name'=>'form2'));
}
		
$smarty->display('u_my_account.tpl');
		
		
?>
<?print_html_body_end();?>
</html>
<?page_close();?>

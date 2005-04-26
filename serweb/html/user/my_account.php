<?php
/*
 * $Id: my_account.php,v 1.1 2005/04/21 15:09:46 kozlik Exp $
 */ 

$_data_layer_required_methods=array('get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session_Pre_Auth",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_apu = array('apu_personal_data', 'apu_user_preferences', 'apu_aliases', 'apu_acl', 'apu_usrloc'); 

require "prepend.php";

if ($controler->come_from_admin_interface){
	/* script is called from admin interface, load page attributes of admin interface */
	require ("../admin/page_attributes.php");
	$page_attributes['selected_tab']="users.php";
}
else{
	$page_attributes['user_name'] = $data->get_user_real_name($serweb_auth, $controler->errors);
}


$pd			= new apu_personal_data();
$usr_pref	= new apu_user_preferences();
$aliases	= new apu_aliases();
$acl		= new apu_acl();
$ul			= new apu_usrloc();

$usr_pref->set_opt('attributes',array('fw_voicemail', 'sw_user_status_visible'));


//create copy of some options from config in order to sensitive options will not accessible via templates
$cfg=new stdclass();
$cfg->enable_dial_voicemail            = $config->enable_dial_voicemail;
$cfg->enable_test_firewall             = $config->enable_test_firewall;

$smarty->assign_by_ref("config", $cfg);

$smarty->assign('url_ctd', "javascript: open_ctd_win('".RawURLEncode("sip:".$controler->user_id->uname."@".$controler->user_id->domain)."');");
$smarty->assign('url_stun', "javascript:stun_applet_win('stun_applet.php', ".$config->stun_applet_width.", ".$config->stun_applet_height.");");
$smarty->assign('url_admin', $sess->url($config->admin_pages_path."users.php?kvrk=".uniqid("")));

$controler->add_reqired_javascript("functions.js");

$controler->add_apu($pd);
$controler->add_apu($usr_pref);
$controler->add_apu($aliases);
$controler->add_apu($acl);
$controler->add_apu($ul);

$controler->assign_form_name("pd", $pd);
$controler->assign_form_name("pd", $usr_pref);
$controler->assign_form_name("ul", $ul);

$controler->set_submit_for_form("ul", 
	array('type' => 'image',
		  'text' => $lang_str['b_add'],
		  'src'  => get_path_to_buttons("btn_add.gif", $sess_lang)));


$controler->set_template_name('u_my_account.tpl');
$controler->start();

?>
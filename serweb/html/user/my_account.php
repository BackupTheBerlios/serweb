<?php
/*
 * $Id: my_account.php,v 1.14 2006/01/03 15:01:20 kozlik Exp $
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('auth', 'attributes', 'usrloc', 'acl', 'uri');

$_required_apu = array('apu_password_update', 'apu_attributes', 'apu_aliases', 'apu_acl', 'apu_usrloc'); 

require "prepend.php";

if ($controler->come_from_admin_interface){
	/* script is called from admin interface, load page attributes of admin interface */
	require ("../admin/page_attributes.php");
	$page_attributes['selected_tab']="users.php";
}
else{
	$page_attributes['user_name'] = get_user_real_name($serweb_auth);
}

$pu			= new apu_password_update();
$usr_pref	= new apu_attributes();
$aliases	= new apu_aliases();
$acl		= new apu_acl();


if ($config->allow_change_usrloc){
	$ul			= new apu_usrloc();
}


/*
 * If you want display only some attributes from user preferences at this tab
 * uncoment block below and choose attributes to display
 */
 
/*
$up_attributes = array();
$up_attributes[] = "fw_voicemail";
$up_attributes[] = "sw_user_status_visible";

$usr_pref->set_opt('attributes', $up_attributes);
*/



/* set descriptions of user preferences */
$att_desc['fw_voicemail'] = $lang_str['ff_fwd_to_voicemail'];
$att_desc['sw_user_status_visible'] = $lang_str['ff_status_visibility'];
$att_desc['send_daily_missed_calls'] = $lang_str['ff_send_daily_missed_calls'];
$usr_pref->set_opt('att_description', $att_desc);

$an = &$config->attr_names;
$attrs_options = array($an['lang'] => array('save_to_session' => true,
                                            'save_to_cookie'  => true)) ;

$usr_pref->set_opt('attrs_options', $attrs_options);
$usr_pref->set_opt('attrs_kind', 'user');


$pu->set_opt('change_pass', $config->allow_change_password);




//create copy of some options from config in order to sensitive options will not accessible via templates
$cfg=new stdclass();
$cfg->enable_dial_voicemail            = $config->enable_dial_voicemail;
$cfg->enable_test_firewall             = $config->enable_test_firewall;
$cfg->allow_change_usrloc              = $config->allow_change_usrloc;


$smarty->assign_by_ref("config", $cfg);

$smarty->assign('url_ctd', "javascript: open_ctd_win_default('".RawURLEncode("sip:".$controler->user_id->uname."@".$controler->user_id->domain)."');");
$smarty->assign('url_stun', "javascript:stun_applet_win('stun_applet.php', ".$config->stun_applet_width.", ".$config->stun_applet_height.");");
$smarty->assign('url_admin', $sess->url($config->admin_pages_path."users.php?kvrk=".uniqid("")));

$controler->add_reqired_javascript("functions.js");



$controler->add_apu($pu);
$controler->add_apu($aliases);
$controler->add_apu($acl);
$controler->add_apu($usr_pref);

$controler->assign_form_name("pd", $pu);
$controler->assign_form_name("pd", $usr_pref);

if ($config->allow_change_usrloc){
	$controler->add_apu($ul);
	$controler->assign_form_name("ul", $ul);

	$controler->set_submit_for_form("ul", 
		array('type' => 'image',
			  'text' => $lang_str['b_add'],
			  'src'  => get_path_to_buttons("btn_add.gif", $sess_lang)));
}



$controler->set_template_name('u_my_account.tpl');
$controler->start();

?>

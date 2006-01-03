<?
/*
 * $Id: user_preferences.php,v 1.6 2006/01/03 15:01:20 kozlik Exp $
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_modules = array('attributes');

$_required_apu = array('apu_attributes');						   
require "prepend.php";


$usr_pref=new apu_attributes();
//$usr_pref->set_opt('attributes',array('aaa', 'aaaa'));

//description of attributes
$att_desc['fw_voicemail'] = $lang_str['ff_fwd_to_voicemail'];
$att_desc['sw_user_status_visible'] = $lang_str['ff_status_visibility'];
$att_desc['send_daily_missed_calls'] = $lang_str['ff_send_daily_missed_calls'];
$usr_pref->set_opt('att_description', $att_desc);


$an = &$config->attr_names;
$attrs_options = array($an['lang'] => array('save_to_session' => true,
                                            'save_to_cookie'  => true)) ;

$usr_pref->set_opt('attrs_options', $attrs_options);
$usr_pref->set_opt('attrs_kind', 'user');


$page_attributes['user_name'] = get_user_real_name($serweb_auth);

$controler->add_apu($usr_pref);
$controler->set_template_name('u_user_preferences.tpl');
$controler->start();

?>

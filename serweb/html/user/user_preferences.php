<?
/*
 * $Id: user_preferences.php,v 1.1 2004/08/25 10:19:48 kozlik Exp $
 */ 

$_data_layer_required_methods=array('get_user_real_name');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_apu = array('apu_user_preferences');						   
require "prepend.php";



$usr_pref=new apu_user_preferences();
//$usr_pref->set_opt('attributes',array('aaa', 'aaaa'));

$page_attributes['user_name']=$data->get_user_real_name($serweb_auth, $controler->errors);

$controler->add_apu($usr_pref);
$controler->set_template_name('u_user_preferences.tpl');
$controler->start();

?>
<?
/*
 * $Id: user_preferences.php,v 1.14 2005/10/19 11:16:11 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('user_preferences');

$_required_apu = array('apu_user_preferences_admin'); 

require "prepend.php";

if ($config->multidomain) $perm->check("admin,hostmaster");
else $perm->check("admin");


$up	= new apu_user_preferences_admin();
$up->set_opt('edit_up_script', 'edit_list_items.php');


$controler->add_apu($up);
$controler->set_template_name('a_user_preferences.tpl');
$controler->start();


?>

<?
/*
 * $Id: edit_list_items.php,v 1.16 2005/10/19 11:16:11 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('user_preferences');

$_required_apu = array('apu_user_preferences_li'); 

require "prepend.php";

if ($config->multidomain) $perm->check("admin,hostmaster");
else $perm->check("admin");

$page_attributes['selected_tab']="user_preferences.php";

$up	= new apu_user_preferences_li();
$up->set_opt('edit_up_script', 'user_preferences.php');


$controler->add_apu($up);
$controler->set_template_name('a_edit_list_items.tpl');
$controler->start();

?>

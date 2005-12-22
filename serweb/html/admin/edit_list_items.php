<?
/*
 * $Id: edit_list_items.php,v 1.17 2005/12/22 12:54:32 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('user_preferences');

$_required_apu = array('apu_user_preferences_li'); 

require "prepend.php";

$perm->check("admin,hostmaster");

$page_attributes['selected_tab']="user_preferences.php";

$up	= new apu_user_preferences_li();
$up->set_opt('edit_up_script', 'user_preferences.php');


$controler->add_apu($up);
$controler->set_template_name('a_edit_list_items.tpl');
$controler->start();

?>

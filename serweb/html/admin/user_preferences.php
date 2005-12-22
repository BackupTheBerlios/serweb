<?
/*
 * $Id: user_preferences.php,v 1.15 2005/12/22 12:54:32 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('user_preferences');

$_required_apu = array('apu_user_preferences_admin'); 

require "prepend.php";

$perm->check("admin,hostmaster");


$up	= new apu_user_preferences_admin();
$up->set_opt('edit_up_script', 'edit_list_items.php');


$controler->add_apu($up);
$controler->set_template_name('a_user_preferences.tpl');
$controler->start();


?>

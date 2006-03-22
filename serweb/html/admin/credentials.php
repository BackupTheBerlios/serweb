<?
/*
 * $Id: credentials.php,v 1.1 2006/03/22 14:00:14 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('credentials');

$_required_apu = array('apu_credentials'); 

require "prepend.php";

$perm->check("admin");


$cr	= new apu_credentials();

$page_attributes['selected_tab']="users.php";

$smarty->assign('uname', $controler->user_id->get_username());
$smarty->assign('domain', $controler->user_id->get_realm());



$controler->add_apu($cr);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_credentials.tpl');
$controler->start();


?>

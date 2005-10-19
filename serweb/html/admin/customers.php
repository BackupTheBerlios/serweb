<?
/*
 * $Id: customers.php,v 1.1 2005/10/19 11:16:11 kozlik Exp $
 */

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain');

$_required_apu = array('apu_customers'); 

require "prepend.php";

$perm->check("admin,hostmaster");


$cu	= new apu_customers();

//$page_attributes['selected_tab']="users.php";


$controler->add_apu($cu);
$controler->set_template_name('a_customers.tpl');
$controler->start();

?>

<?
/**
 *	Edit customers
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: customers.php,v 1.4 2007/02/14 16:36:39 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain');

$_required_apu = array('apu_customers', 'apu_sorter'); 

/** include all others necessary files */
require "prepend.php";

$perm->check("admin,hostmaster");


$cu	= new apu_customers();
$sr = new apu_sorter();

$cu->set_sorter($sr);

//$page_attributes['selected_tab']="users.php";


$controler->add_apu($cu);
$controler->add_apu($sr);
$controler->set_template_name('a_customers.tpl');
$controler->start();

?>

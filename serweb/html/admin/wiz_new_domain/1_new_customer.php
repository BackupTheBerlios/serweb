<?php
/**
 *	New domain wizard - create new customer
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: 1_new_customer.php,v 1.3 2007/02/14 16:36:39 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain');

$_required_apu = array('apu_customers'); 

/** include all others necessary files */
require "prepend.php";

$page_attributes['title'] .= " - ".$lang_str['step']." 1/3";

$perm->check("admin,hostmaster");


$cu	= new apu_customers();
$cu->set_opt('redirect_on_create', "1_new_domain.php");

$controler->add_apu($cu);
$controler->set_template_name('a_wiz_new_domain/1_new_customer.tpl');
$controler->start();

?>

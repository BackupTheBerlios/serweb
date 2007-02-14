<?php
/**
 *	New domain wizard - enter domain name
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: 1_new_domain.php,v 1.5 2007/02/14 16:36:39 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain');

$_required_apu = array('apu_domain'); 

/** include all others necessary files */
require "prepend.php";

$perm->check("admin,hostmaster");

$page_attributes['title'] .= " - ".$lang_str['step']." 1/3";

$do	= new apu_domain();
$do->set_opt('redirect_on_update', '2_new_admin.php?save_domain_id=1');

$do->set_opt('prohibited_domain_names', $config->prohibited_domains);

if (isset($_GET['new_cust_id']))
	$do->set_opt('preselected_customer', $_GET['new_cust_id']);


$controler->add_apu($do);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_wiz_new_domain/1_new_domain.tpl');
$controler->start();


?>

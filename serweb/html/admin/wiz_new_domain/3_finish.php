<?php
/*
 * $Id: 3_finish.php,v 1.2 2005/12/22 12:54:33 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain', 'privileges');

$_required_apu = array('apu_domain_admin', 'apu_privileges'); 

require "prepend.php";

$perm->check("admin,hostmaster");

$page_attributes['title'] .= " - ".$lang_str['step']." 3/3";

/* restore ID of domain from session variable */
if (!empty($sess_wiz_new_domain['domain_id'])) 
	$controler->set_domain_id($sess_wiz_new_domain['domain_id']);


$da	= new apu_domain_admin();
$da->set_opt('get_list_of_domains', false);

$pr	= new apu_privileges();


$controler->add_apu($da);
$controler->add_apu($pr);
//$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_wiz_new_domain/3_finish.tpl');
$controler->start();


?>

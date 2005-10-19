<?php
/*
 * $Id: domain_preferences.php,v 1.1 2005/10/19 11:16:11 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('domain_preferences');

$_required_apu = array('apu_domain_preferences'); 


require "prepend.php";

$perm->check("admin");

$page_attributes['selected_tab']="list_of_domains.php";

$dp	= new apu_domain_preferences();
$dp->set_opt('redirect_on_update', 'list_of_domains.php');

$controler->add_apu($dp);
$controler->set_template_name('a_domain_preferences.tpl');
$controler->start();

?>

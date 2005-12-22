<?php
/*
 * $Id: global_attributes.php,v 1.1 2005/12/22 12:56:05 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('attributes');

$_required_apu = array('apu_attributes'); 


require "prepend.php";

$perm->check("admin,hostmaster");

$gp	= new apu_attributes();
$gp->set_opt('attrs_kind', 'global');

$controler->add_apu($gp);
$controler->set_template_name('a_global_attributes.tpl');
$controler->start();

?>

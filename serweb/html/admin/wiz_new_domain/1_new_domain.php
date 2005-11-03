<?php
/*
 * $Id: 1_new_domain.php,v 1.1 2005/11/03 11:02:08 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('multidomain');

$_required_apu = array('apu_domain'); 

require "prepend.php";

$perm->check("admin,hostmaster");

$page_attributes['title'] .= " - ".$lang_str['step']." 1/3";

$do	= new apu_domain();
$do->set_opt('redirect_on_update', '2_new_admin.php?save_domain_id=1');

$controler->add_apu($do);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_wiz_new_domain/1_new_domain.tpl');
$controler->start();


?>

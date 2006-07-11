<?php
/*
 * $Id: global_attributes.php,v 1.2 2006/07/11 12:14:58 kozlik Exp $
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

if ($perm->have_perm('hostmaster')) $gp->set_opt('perm', 'hostmaster');
else                                $gp->set_opt('perm', 'admin');

$controler->add_apu($gp);
$controler->set_template_name('a_global_attributes.tpl');
$controler->start();

?>

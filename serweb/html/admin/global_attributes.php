<?php
/**
 *	Edit global attributes
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: global_attributes.php,v 1.3 2007/02/14 16:36:39 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('attributes');

$_required_apu = array('apu_attributes'); 


/** include all others necessary files */
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

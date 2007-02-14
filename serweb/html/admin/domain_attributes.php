<?php
/**
 *	Edit domain attributes
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: domain_attributes.php,v 1.3 2007/02/14 16:36:39 kozlik Exp $
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

$perm->check("admin");

$page_attributes['selected_tab']="list_of_domains.php";

$dp	= new apu_attributes();
$dp->set_opt('redirect_on_update', 'list_of_domains.php');
$dp->set_opt('attrs_kind', 'domain');

if ($perm->have_perm('hostmaster')) $dp->set_opt('perm', 'hostmaster');
else                                $dp->set_opt('perm', 'admin');

$controler->add_apu($dp);
$controler->set_template_name('a_domain_attributes.tpl');
$controler->start();

?>

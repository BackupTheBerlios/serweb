<?php
/**
 *	Edit attribute types
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: attr_types.php,v 1.5 2007/11/12 12:45:05 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('attributes');

$_required_apu = array('apu_attr_types', 'apu_sorter', 'apu_filter'); 

/** include all others necessary files */
require "prepend.php";

$perm->check("admin,hostmaster");

$at  	= new apu_attr_types();
$sr 	= new apu_sorter();
$filter	= new apu_filter();

$filter->set_opt('partial_match', false);
$filter->set_opt('smarty_form', 'filter_form');

$at->set_filter($filter);
$at->set_sorter($sr);

$at->set_opt("dtdfile", $config->root_uri.$config->admin_pages_path."attr_types.dtd.php");

$controler->add_apu($at);
$controler->add_apu($sr);
$controler->add_apu($filter);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_attr_types.tpl');
$controler->start();

?>

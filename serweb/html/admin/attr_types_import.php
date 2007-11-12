<?php
/**
 *  Import attribute types
 * 
 *  @author     Karel Kozlik
 *  @version    $Id: attr_types_import.php,v 1.1 2007/11/12 12:45:05 kozlik Exp $
 *  @package    serweb
 *  @subpackage admin_pages
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
                           "auth" => "phplib_Auth",
                           "perm" => "phplib_Perm");

$_required_modules = array('attributes');

$_required_apu = array('apu_attr_types_import'); 

/** include all others necessary files */
require "prepend.php";

$perm->check("admin,hostmaster");

$at     = new apu_attr_types_import();

$page_attributes['selected_tab']="attr_types.php";

$controler->add_apu($at);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name('a_attr_types_import.tpl');
$controler->start();

?>

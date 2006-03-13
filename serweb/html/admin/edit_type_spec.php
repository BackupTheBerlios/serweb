<?php
/*
 * $Id: edit_type_spec.php,v 1.1 2006/03/13 15:34:05 kozlik Exp $
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth",
						   "perm" => "phplib_Perm");

$_required_modules = array('attributes');

$_required_apu = array(); 

require "prepend.php";

$perm->check("admin,hostmaster");

$page_attributes['selected_tab']="attr_types.php";


/*
 *	Get name of attribute 
 */

if (isset($_GET['attrib_name'])) {
	$attr_name = $_GET['attrib_name'];
}
else{
	$attr_name = $_SESSION['edit_type_spec']['attrib_name'];
}

if (!$attr_name) die("Internal error - unknown attribute name");


/*
 *	Get type of attribute
 */
$attrs = &Attr_types::singleton();
if (false === $at = $attrs->get_attr_type($attr_name)) die("Internal error - can't get type of attribute");
if (is_null($at)) die("Internal error - unknown attribute");

/*
 *	Get name of APU for edit 'type_spec'
 */
$apu_name  = $at->apu_edit();
if (!$apu_name) die("Internal error - unknown APU");

/*
 *	Load apu
 */
load_apu($apu_name);


/*
 *	Store name of attribute into session
 */
$_SESSION['edit_type_spec']['attrib_name'] = $attr_name;


$apu	= new $apu_name();
$apu->set_opt('attr_name', $attr_name);

$template = 'a_attr_types_'.$apu->get_template_name().'.tpl';

$controler->add_apu($apu);
$controler->add_reqired_javascript('functions.js');
$controler->set_template_name($template);
$controler->start();

?>

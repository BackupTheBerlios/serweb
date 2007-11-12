<?php
/**
 *	This file providing access to the attr_types.dtd file from attributes module
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: attr_types.dtd.php,v 1.1 2007/11/12 12:45:05 kozlik Exp $
 *	@package    serweb
 */ 

Header("Content-Disposition: attachment;filename=attr_types.dtd");
header("Content-Type: application/xml-dtd");

/** Include the javascript file */
require("../../modules/attributes/attr_types.dtd");

?>

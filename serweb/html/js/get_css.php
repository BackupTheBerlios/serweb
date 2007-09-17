<?php
/**
 *	This file providing access to the stylesheet files specific for a module
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: get_css.php,v 1.1 2007/09/17 18:56:31 kozlik Exp $
 *	@package    serweb
 *	@subpackage framework
 */ 

Header("content-type: text/css");

/** Include the javascript file */
require("../../modules/".$_GET['mod']."/".$_GET['css'])

?>

<?php
/**
 *	This file providing access to the javascript files specifing for an module
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: get_js.php,v 1.2 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage framework
 */ 

Header("content-type: text/js");

/** Include the javascript file */
require("../../modules/".$_GET['mod']."/".$_GET['js'])

?>

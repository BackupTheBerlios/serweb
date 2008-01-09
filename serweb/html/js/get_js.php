<?php
/**
 *	This file providing access to the javascript files specifing for an module
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: get_js.php,v 1.3 2008/01/09 15:26:00 kozlik Exp $
 *	@package    serweb
 *	@subpackage framework
 */ 

Header("content-type: text/js");

/**
 *  Do not allow to get file with ".." in their name
 *  This is for security reasons
 */
if (false !== strpos($_GET['js'], ".."))  die ("Prohibited file name");
if (false !== strpos($_GET['mod'], "..")) die ("Prohibited module name");

/** Include the javascript file */
require("../../modules/".$_GET['mod']."/".$_GET['js'])

?>

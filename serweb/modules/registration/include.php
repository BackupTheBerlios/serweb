<?php
/**
 *	File automaticaly included by the framework when module is loaded
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: include.php,v 1.3 2007/09/21 14:21:21 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_registration
 */ 

/**
 *	module init function
 *
 *	Is called when all files are included
 */
function registration_init(){
}

require_once(dirname(__FILE__) . "/classes.php");
include_module('multidomain');

?>

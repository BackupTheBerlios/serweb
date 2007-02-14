<?php
/**
 *	Javascript function for login name completion
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: login_completion.js.php,v 1.5 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage js
 */ 

Header("content-type: text/js");

/**  */
require("../set_dirs.php");

require ($_SERWEB["serwebdir"] . "class_definitions.php");
require ($_SERWEB["serwebdir"] . "../config/config_paths.php");
require ($_SERWEB["serwebdir"] . "../config/set_domain.php");
require ($_SERWEB["serwebdir"] . "../config/config.php");
require ($_SERWEB["serwebdir"] . "functions.php");

$reg = new Creg;				// create regular expressions class
?>

function login_completion(adr){
	var default_domain='<?echo $config->domain;?>';

	var re = /^([^@]+)$/i;
	if (re.test(adr.value)) {
		adr.value=adr.value+'@'+default_domain;
	}

}


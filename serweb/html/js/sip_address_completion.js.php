<?php
/**
 *	Javascript function for SIP URIs completion
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: sip_address_completion.js.php,v 1.6 2007/02/14 16:36:40 kozlik Exp $
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

function sip_address_completion(adr){
	var default_domain='<?echo $config->domain;?>';

	var re = /^<?echo str_replace('/','\/',$reg->user);?>$/i;
	if (re.test(adr.value)) {
		adr.value=adr.value+'@'+default_domain;
	}

	var re = /^<?echo str_replace('/','\/',$reg->address);?>$/i
	var re2= /^sip:/i;
	if (re.test(adr.value) && !re2.test(adr.value)) {
		adr.value='sip:'+adr.value;
	}
}


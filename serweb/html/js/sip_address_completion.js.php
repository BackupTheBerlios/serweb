<?
/*
 * $Id: sip_address_completion.js.php,v 1.4 2004/09/23 11:51:55 kozlik Exp $
 */

Header("content-type: text/js");

$_SERWEB = array();
$_SERWEB["serwebdir"]  = "../";

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


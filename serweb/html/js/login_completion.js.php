<?
/*
 * $Id: login_completion.js.php,v 1.3 2004/09/23 11:51:55 kozlik Exp $
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

function login_completion(adr){
	var default_domain='<?echo $config->domain;?>';

	var re = /^([^@]+)$/i;
	if (re.test(adr.value)) {
		adr.value=adr.value+'@'+default_domain;
	}

}


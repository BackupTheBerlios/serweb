<?
/*
 * $Id: login_completion.js.php,v 1.1 2004/08/09 13:08:10 kozlik Exp $
 */

Header("content-type: text/js");

$_SERWEB = array();
$_SERWEB["serwebdir"]  = "../";

require ($_SERWEB["serwebdir"] . "class_definitions.php");
require ($_SERWEB["serwebdir"] . "config_paths.php");
require ($_SERWEB["serwebdir"] . "set_domain.php");
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


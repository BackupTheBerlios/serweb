<?
/*
 * $Id: index.php,v 1.5 2006/12/01 16:41:20 kozlik Exp $
 *
 * Redirect to user interface
 *
 */

require("./set_dirs.php");

require_once ($_SERWEB["functionsdir"] . "class_definitions.php");
require_once ($_SERWEB["configdir"] . "config_paths.php");
require_once ($_SERWEB["configdir"] . "config.php");

if ($config->testing_facility){
	Header("Location: test/index.php");
}
else{
	Header("Location: ".$config->user_pages_path."index.php");
}

?>

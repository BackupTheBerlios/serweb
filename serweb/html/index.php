<?php
/**
 *	First access point to apllication
 * 
 *	Page which redirecting user either to testing interface or to user interface
 *
 *	@author    Karel Kozlik
 *	@version   $Id: index.php,v 1.6 2007/02/14 16:36:39 kozlik Exp $ 
 *	@package   serweb
 */ 

/** include paths to directories */
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

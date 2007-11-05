<?php
/**
 *	First access point to apllication
 * 
 *	Page which redirecting user either to testing interface or to user interface
 *
 *	@author    Karel Kozlik
 *	@version   $Id: index.php,v 1.7 2007/11/05 15:00:58 kozlik Exp $ 
 *	@package   serweb
 */ 

/** include paths to directories */
require("./set_dirs.php");

require($_SERWEB["serwebdir"] . "main_prepend.php");

if ($config->testing_facility){
	Header("Location: test/index.php");
}
else{
	Header("Location: ".$config->user_pages_path."index.php");
}

?>

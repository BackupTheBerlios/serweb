<?php
/**
 *	Load all required files
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: prepend.php,v 1.12 2007/09/17 18:56:31 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

/** */
$_dir = dirname(__FILE__);
require($_dir."/../set_dirs.php");

require($_SERWEB["serwebdir"] . "main_prepend.php");
require($_SERWEB["serwebdir"] . "load_phplib.php");

	phplib_load("sess");
	
require($_SERWEB["serwebdir"] . "load_lang.php");
require("page_attributes.php");

	phplib_load(array("auth", "perm"));

require($_SERWEB["serwebdir"] . "load_apu.php");

	init_modules();

?>

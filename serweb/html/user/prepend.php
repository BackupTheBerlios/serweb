<?php
/**
 *	Load all required files
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: prepend.php,v 1.6 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

/**  */
require("../set_dirs.php");

require($_SERWEB["serwebdir"] . "main_prepend.php");
require($_SERWEB["serwebdir"] . "load_phplib.php");

	phplib_load("sess");

require($_SERWEB["serwebdir"] . "load_lang.php");
require("page_attributes.php");

	phplib_load(array("auth", "perm"));

require($_SERWEB["serwebdir"] . "load_apu.php");

	init_modules();

?>

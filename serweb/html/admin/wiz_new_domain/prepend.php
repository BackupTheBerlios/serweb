<?php
/**
 *	New domain wizard - include all required files
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: prepend.php,v 1.5 2007/02/14 16:36:39 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

/**  */
require("../../set_dirs.php");

require($_SERWEB["serwebdir"] . "main_prepend.php");
require($_SERWEB["serwebdir"] . "load_phplib.php");

	phplib_load("sess");

require($_SERWEB["serwebdir"] . "load_lang.php");
require("page_attributes.php");

	phplib_load(array("auth", "perm"));

require($_SERWEB["serwebdir"] . "load_apu.php");

	init_modules();

?>

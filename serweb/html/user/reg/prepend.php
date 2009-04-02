<?php
/**
 *	Load all required files
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: prepend.php,v 1.7 2009/04/02 16:26:34 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

/** */
require("../../set_dirs.php");

require($_SERWEB["serwebdir"] . "main_prepend.php");
require($_SERWEB["serwebdir"] . "load_phplib.php");

	phplib_load("sess");
	
require($_SERWEB["serwebdir"] . "load_lang.php");
require("page_attributes.php");

	phplib_load(array("auth", "perm"));

require($_SERWEB["serwebdir"] . "load_apu.php");

	$controler->add_reqired_javascript('phplib.js');
	init_modules();

?>

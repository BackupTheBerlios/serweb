<?php
/*
 * $Id: prepend.php,v 1.3 2005/11/30 09:58:16 kozlik Exp $
 */

$_SERWEB = array();
$_PHPLIB = array();

# Can't control your include path?
# Point this to your PHPLIB base directory. Use a trailing "/"!
$_SERWEB["serwebdir"]  = "../../";
$_PHPLIB["libdir"]  = "../../../phplib/";

require($_SERWEB["serwebdir"] . "main_prepend.php");
require($_SERWEB["serwebdir"] . "load_phplib.php");

	phplib_load("sess");
	init_modules();
	
require($_SERWEB["serwebdir"] . "load_lang.php");
require("page_attributes.php");

	phplib_load(array("auth", "perm"));

require($_SERWEB["serwebdir"] . "load_apu.php");

?>

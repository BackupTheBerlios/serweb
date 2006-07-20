<?php
/*
 * $Id: prepend.php,v 1.4 2006/07/20 18:44:39 kozlik Exp $
 */ 

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

<?php
/*
 * $Id: prepend.php,v 1.6 2004/08/09 11:37:12 kozlik Exp $
 */

$_SERWEB = array();
$_PHPLIB = array();

# Can't control your include path?
# Point this to your PHPLIB base directory. Use a trailing "/"!
$_SERWEB["serwebdir"]  = "../../";
$_PHPLIB["libdir"]  = "../../../phplib/";

require($_SERWEB["serwebdir"] . "main_prepend.php");
require($_SERWEB["serwebdir"] . "load_phplib.php");

require("page_attributes.php");
?>
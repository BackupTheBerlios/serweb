<?php
/*
 * $Id: prepend.php,v 1.2 2007/11/19 16:14:09 kozlik Exp $
 */

$_SERWEB = array();
$_PHPLIB = array();

# Can't control your include path?
# Point this to your PHPLIB base directory. Use a trailing "/"!
$_SERWEB["serwebdir"]  = "../html/";
$_PHPLIB["libdir"]  = "../phplib/";

require($_SERWEB["serwebdir"] . "set_dirs.php");
require($_SERWEB["serwebdir"] . "main_prepend.php");

$page_attributes=array();
?>

<?php
/*
 * $Id: prepend.php,v 1.1 2004/10/12 14:42:20 kozlik Exp $
 */

$_SERWEB = array();
$_PHPLIB = array();

# Can't control your include path?
# Point this to your PHPLIB base directory. Use a trailing "/"!
$_SERWEB["serwebdir"]  = "../../html/";
$_PHPLIB["libdir"]  = "../../phplib/";

require($_SERWEB["serwebdir"] . "main_prepend.php");

$page_attributes=array();
?>

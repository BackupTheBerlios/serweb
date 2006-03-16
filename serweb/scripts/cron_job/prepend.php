<?php
/*
 * $Id: prepend.php,v 1.4 2006/03/16 11:56:20 kozlik Exp $
 */

$_SERWEB = array();
$_PHPLIB = array();

# Can't control your include path?
# Point this to your PHPLIB base directory. Use a trailing "/"!
$_SERWEB["serwebdir"]  = dirname(__FILE__)."/../../html/";
$_PHPLIB["libdir"]     = dirname(__FILE__)."/../../phplib/";

require($_SERWEB["serwebdir"] . "main_prepend.php");

require($_SERWEB["serwebdir"] . "load_lang.php");

$page_attributes=array();
?>

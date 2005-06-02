<?php
/*
 * $Id: prepend.php,v 1.2 2005/06/02 11:27:19 kozlik Exp $
 */

$_SERWEB = array();
$_PHPLIB = array();

# Can't control your include path?
# Point this to your PHPLIB base directory. Use a trailing "/"!
$_SERWEB["serwebdir"]  = dirname(__FILE__)."/../../html/";
$_PHPLIB["libdir"]     = dirname(__FILE__)."/../../phplib/";

require($_SERWEB["serwebdir"] . "main_prepend.php");

$page_attributes=array();
?>

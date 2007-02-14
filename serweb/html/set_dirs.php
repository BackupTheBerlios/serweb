<?php
/**
 *	File describing directory structure (where the others files could be found)
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: set_dirs.php,v 1.2 2007/02/14 16:36:39 kozlik Exp $
 *	@package    serweb
 *	@subpackage framework
 */ 


$_SERWEB = array();
$_PHPLIB = array();

$dir = realpath(dirname(__FILE__)."/..");
$_SERWEB["serwebdir"]  = $dir."/html/";
$_SERWEB["configdir"]  = $dir."/config/";
$_SERWEB["datadir"]  = $dir."/data_layer/";
$_SERWEB["appdir"]  = $dir."/application_layer/";
$_SERWEB["functionsdir"]  = $dir."/html/";
$_SERWEB["langdir"]  = $dir."/lang/";
$_SERWEB["modulesdir"]  = $dir."/modules/";
$_SERWEB["smartydir"]  = $dir."/smarty/";
//$_SERWEB["pagesdir"]  = $dir."/pages/";

$_PHPLIB["libdir"]  = $dir."/phplib/";
unset ($dir);

?>

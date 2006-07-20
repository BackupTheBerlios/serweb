<?php
/*
 * $Id: set_dirs.php,v 1.1 2006/07/20 18:44:38 kozlik Exp $
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
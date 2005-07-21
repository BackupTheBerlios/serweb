<?
/*
 * $Id: index.php,v 1.3 2005/07/21 16:23:03 kozlik Exp $
 *
 * Redirect to user interface
 *
 */

$_SERWEB = array();
$_SERWEB["serwebdir"]  = "./";

require "../config/config_paths.php";
Header("Location: ".$config->user_pages_path."index.php");

?>

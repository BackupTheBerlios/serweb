<?
/*
 * $Id: index.php,v 1.1 2004/11/05 19:41:09 kozlik Exp $
 *
 * Redirect to user interface
 *
 */

require "../config/config_paths.php";
Header("Location: ".$config->user_pages_path."index1.php");

?>

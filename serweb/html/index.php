<?
/*
 * $Id: index.php,v 1.2 2004/11/25 11:23:11 kozlik Exp $
 *
 * Redirect to user interface
 *
 */

require "../config/config_paths.php";
Header("Location: ".$config->user_pages_path."index.php");

?>

<?
/*
 * $Id: index.php,v 1.4 2006/07/20 18:44:29 kozlik Exp $
 *
 * Redirect to user interface
 *
 */

require("./set_dirs.php");

require "../config/config_paths.php";
Header("Location: ".$config->user_pages_path."index.php");

?>

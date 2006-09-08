<?
/*
 * $Id: logout.php,v 1.6 2006/09/08 12:27:32 kozlik Exp $
 */

$_phplib_page_open = array("sess" => "phplib_Session");

require "prepend.php";

$_SESSION['auth']->logout();
$sess->unregister("sess_admin");

$sess->delete();
Header("Location: ".$sess->url("index.php?kvrk=".uniqID("")."&logout=1"));
page_close(); ?>

<?
/*
 * $Id: logout.php,v 1.5 2004/08/10 17:33:50 kozlik Exp $
 */

$_phplib_page_open = array("sess" => "phplib_Session");

require "prepend.php";

$sess->unregister("auth");
$sess->unregister("sess_admin");
$sess->unregister("serweb_auth");

$sess->delete();
Header("Location: ".$sess->url("index.php?kvrk=".uniqID("")."&logout=1"));
page_close(); ?>

<?
/*
 * $Id: logout.php,v 1.4 2004/08/09 12:21:27 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session"));

$sess->unregister("auth");
$sess->unregister("sess_admin");
$sess->unregister("serweb_auth");

$sess->delete();
Header("Location: ".$sess->url("index.php?kvrk=".uniqID("")."&logout=1"));
page_close(); ?>

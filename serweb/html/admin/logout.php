<?
/*
 * $Id: logout.php,v 1.3 2002/09/10 15:59:35 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session"));

$sess->unregister("auth");
$sess->unregister("sess_admin");

$sess->delete();
Header("Location: ".$sess->url("index.php?kvrk=".uniqID("")."&message=".RAWUrlEncode("You have been logged out")));
page_close(); ?>

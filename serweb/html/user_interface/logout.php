<?
/*
 * $Id: logout.php,v 1.5 2004/04/04 19:42:14 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session"));

$sess->unregister("auth");

if (isset($sess_admin) and $sess_admin){ //when admins logged out from user's "my_account page"
	$sess->unregister("sess_admin");
	Header("Location: ".$sess->url("../admin/index.php?kvrk=".uniqID("")."&message=".RAWUrlEncode("You have been logged out")));
}
else
	Header("Location: ".$sess->url("index.php?kvrk=".uniqID("")."&message=".RAWUrlEncode("You have been logged out")));

$sess->delete();
page_close(); ?>

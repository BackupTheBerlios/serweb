<?
/*
 * $Id: logout.php,v 1.4 2002/09/10 15:59:35 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session"));

$sess->unregister("auth");

if ($sess_admin){ //when admins logged out from user's "my_account page"
	$sess->unregister("sess_admin");
	Header("Location: ".$sess->url("../admin/index.php?kvrk=".uniqID("")."&message=".RAWUrlEncode("You have been logged out")));
}
else
	Header("Location: ".$sess->url("index.php?kvrk=".uniqID("")."&message=".RAWUrlEncode("You have been logged out")));

$sess->delete();
page_close(); ?>

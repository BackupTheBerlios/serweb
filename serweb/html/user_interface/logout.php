<?
/*
 * $Id: logout.php,v 1.6 2004/08/09 12:21:27 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session"));

$sess->unregister("auth");
$sess->unregister("serweb_auth");

if (isset($sess_admin) and $sess_admin){ //when admins logged out from user's "my_account page"
	$sess->unregister("sess_admin");
	Header("Location: ".$sess->url($config->admin_pages_path."index.php?kvrk=".uniqID("")."&logout=1"));
}
else
	Header("Location: ".$sess->url("index.php?kvrk=".uniqID("")."&logout=1"));

$sess->delete();
page_close(); ?>

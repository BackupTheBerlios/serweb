<?
require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session"));

//$auth->logout();
//$sess->delete();
$sess->unregister("auth");

Header("Location: ".$sess->url("index.php?kvrk=".uniqID("")."&message=".RAWUrlEncode("You have been logged out")));
page_close(); ?>

<?php
/**
 *	Logout
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: logout.php,v 1.3 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$_phplib_page_open = array("sess" => "phplib_Session");

/** include all others necessary files */
require "prepend.php";

$_SESSION['auth']->logout();

if (isset($sess_admin) and $sess_admin){ //when admins logged out from user's "my_account page"
	$sess->unregister("sess_admin");
	Header("Location: ".$sess->url($config->admin_pages_path."index.php?kvrk=".uniqID("")."&logout=1"));
}
else
	Header("Location: ".$sess->url("index.php?kvrk=".uniqID("")."&logout=1"));

$sess->delete();
page_close(); ?>

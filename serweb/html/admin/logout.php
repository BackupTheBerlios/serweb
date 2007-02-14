<?php
/**
 *	Logout
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: logout.php,v 1.7 2007/02/14 16:36:39 kozlik Exp $
 *	@package    serweb
 *	@subpackage admin_pages
 */ 

$_phplib_page_open = array("sess" => "phplib_Session");

/** include all others necessary files */
require "prepend.php";

$_SESSION['auth']->logout();
$sess->unregister("sess_admin");

$sess->delete();
Header("Location: ".$sess->url("index.php?kvrk=".uniqID("")."&logout=1"));
page_close(); ?>

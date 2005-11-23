<?
/*
 * Require all files needed by phplib 
 *
 * $Id: load_phplib.php,v 1.9 2005/11/23 09:59:26 kozlik Exp $
 */ 


require($_PHPLIB["libdir"] . "session4.1.php");   /* Required for everything below.      */
require($_PHPLIB["libdir"] . "auth4.1.php");      /* Disable this, if you are not using authentication. */
require($_PHPLIB["libdir"] . "perm4.1.php");      /* Disable this, if you are not using permission checks. */


require($_PHPLIB["libdir"] . "local/local.inc");     /* Required, contains your local configuration. */
require($_PHPLIB["libdir"] . "page4.1.php");      /* Required, contains the page management functions. */
require($_PHPLIB["libdir"] . "oohforms.inc");  /* Required for object oriented HTML forms. */

if (isset($_phplib_page_open)){
	put_headers();
	page_open ($_phplib_page_open);
	unset($_phplib_page_open);
}
?>

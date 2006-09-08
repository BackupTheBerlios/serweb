<?
/*
 * Require all files needed by phplib 
 *
 * $Id: load_phplib.php,v 1.11 2006/09/08 12:27:32 kozlik Exp $
 */ 


require($_PHPLIB["libdir"] . "session4.1.php");   /* Required for everything below.      */
require($_PHPLIB["libdir"] . "auth4.1.php");      /* Disable this, if you are not using authentication. */
require($_PHPLIB["libdir"] . "perm4.1.php");      /* Disable this, if you are not using permission checks. */


require($_PHPLIB["libdir"] . "local/local.inc");     /* Required, contains your local configuration. */
require($_PHPLIB["libdir"] . "page4.1.php");      /* Required, contains the page management functions. */
require($_PHPLIB["libdir"] . "oohforms.inc");  /* Required for object oriented HTML forms. */

function phplib_load($features = null){

	if (is_null($features)) $features = array('sess', 'auth', 'perm');
	if (is_string($features)) $features = array($features);

	if (isset($GLOBALS['_phplib_page_open'])){
		if (in_array('sess', $features)) put_headers();

		page_open ($GLOBALS['_phplib_page_open'], $features);
	}

}

?>

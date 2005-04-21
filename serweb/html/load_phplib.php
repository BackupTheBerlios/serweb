<?
/*
 * Require all files needed by phplib 
 *
 * $Id: load_phplib.php,v 1.7 2005/04/21 15:09:46 kozlik Exp $
 */ 


if ($config->data_sql->type=="mysql"){
	require($_PHPLIB["libdir"] . "db_mysql.inc");  /* Load ct_sql class for MySQL database. */
}
elseif ($config->data_sql->type=="pgsql"){
	require($_PHPLIB["libdir"] . "db_pgsql.inc");  /* Load ct_sql class for PostgreSQL database. */
}
else die('Invalid database type '.$config->data_sql->type);

require($_PHPLIB["libdir"] . "ct_sql.inc");    /* Change this to match your data storage container */
require($_PHPLIB["libdir"] . "session4.inc");   /* Required for everything below.      */
require($_PHPLIB["libdir"] . "auth4.inc");      /* Disable this, if you are not using authentication. */
require($_PHPLIB["libdir"] . "perm.inc");      /* Disable this, if you are not using permission checks. */
//require($_PHPLIB["libdir"] . "user4.inc");      /* Disable this, if you are not using per-user variables. */

/* Additional require statements go below this line */
// require($_PHPLIB["libdir"] . "menu.inc");      /* Enable to use Menu */

/* Additional require statements go before this line */

require($_PHPLIB["libdir"] . "local/local.inc");     /* Required, contains your local configuration. */

require($_PHPLIB["libdir"] . "page4.inc");      /* Required, contains the page management functions. */

require($_PHPLIB["libdir"] . "oohforms.inc");  /* Required for object oriented HTML forms. */

if (isset($_phplib_page_open)){ //backward compatibility
	put_headers();
	page_open ($_phplib_page_open);
	unset($_phplib_page_open);
}
?>
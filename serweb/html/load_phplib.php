<?
/*
 * Require all files needed by phplib 
 *
 * $Id: load_phplib.php,v 1.1 2004/03/24 21:39:46 kozlik Exp $
 */ 


 
require($_PHPLIB["libdir"] . "db_mysql.inc");  /* Change this to match your database. */
require($_PHPLIB["libdir"] . "ct_sql.inc");    /* Change this to match your data storage container */
require($_PHPLIB["libdir"] . "session.inc");   /* Required for everything below.      */
require($_PHPLIB["libdir"] . "auth.inc");      /* Disable this, if you are not using authentication. */
require($_PHPLIB["libdir"] . "perm.inc");      /* Disable this, if you are not using permission checks. */
//require($_PHPLIB["libdir"] . "user.inc");      /* Disable this, if you are not using per-user variables. */

/* Additional require statements go below this line */
// require($_PHPLIB["libdir"] . "menu.inc");      /* Enable to use Menu */

/* Additional require statements go before this line */

require($_PHPLIB["libdir"] . "local.inc");     /* Required, contains your local configuration. */

require($_PHPLIB["libdir"] . "page.inc");      /* Required, contains the page management functions. */

require($_PHPLIB["libdir"] . "oohforms.inc");  /* Required for object oriented HTML forms. */

?>
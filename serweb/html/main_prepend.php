<?
/*
 * $Id: main_prepend.php,v 1.1 2004/03/24 21:39:46 kozlik Exp $
 */ 

//require class defintions
require ($_SERWEB["serwebdir"] . "class_definitions.php");

//require paths configuration
require ($_SERWEB["serwebdir"] . "config_paths.php");

//set $config->domain
require ($_SERWEB["serwebdir"] . "set_domain.php");

//require domain depending config
require ($_SERWEB["serwebdir"] . "config_domain.php");
$domain_config=new CDomain_config();

//TO DO: load language

//require sql access configuration and table names
require ($_SERWEB["serwebdir"] . "config_sql.php");

//require other configuration
require ($_SERWEB["serwebdir"] . "config.php");

//activate domain depending config
$domain_config->activate_domain_config();
unset($domain_config);

//require functions
require ($_SERWEB["serwebdir"] . "functions.php");

//require functions for work with data store
require ($_SERWEB["serwebdir"] . "sql_and_fifo_functions.php");

//require page layout
require ($_SERWEB["serwebdir"] . "page.php");

?>
<?
/*
 * $Id: main_prepend.php,v 1.2 2004/04/04 19:42:14 kozlik Exp $
 */ 

//require class defintions
require_once ($_SERWEB["serwebdir"] . "class_definitions.php");

//require paths configuration
require_once ($_SERWEB["serwebdir"] . "config_paths.php");

//set $config->domain
require_once ($_SERWEB["serwebdir"] . "set_domain.php");

//require domain depending config
require_once ($_SERWEB["serwebdir"] . "config_domain.php");
$domain_config=new CDomain_config();

//TO DO: load language

//require sql access configuration and table names
require_once ($_SERWEB["serwebdir"] . "config_sql.php");

//require other configuration
require_once ($_SERWEB["serwebdir"] . "config.php");

//activate domain depending config
$domain_config->activate_domain_config();
unset($domain_config);

//require PEAR DB
require_once 'DB.php';

//require PEAR Log
require_once 'Log.php';

//create log instance
$serwebLog  = &Log::singleton('file', $config->log_file, 'serweb', array(), PEAR_LOG_INFO);


//require functions
require_once ($_SERWEB["serwebdir"] . "functions.php");

//require functions for work with data store
require_once ($_SERWEB["serwebdir"] . "sql_and_fifo_functions.php");

//require page layout
require_once ($_SERWEB["serwebdir"] . "page.php");

?>
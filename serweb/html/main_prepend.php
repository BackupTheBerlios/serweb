<?php
/**
 *	File loading configs, functions, class definitions etc.
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: main_prepend.php,v 1.16 2007/10/04 21:34:16 kozlik Exp $
 *	@package    serweb
 *	@subpackage framework
 */ 

/** require class defintions */
require_once ($_SERWEB["functionsdir"] . "class_definitions.php");

/** require paths configuration */
require_once ($_SERWEB["configdir"] . "config_paths.php");

/** set $config->domain */
require_once ($_SERWEB["configdir"] . "set_domain.php");

/** require domain depending config - default values */
require_once ($_SERWEB["serwebdir"] . "config_domain.php");

/** require sql access configuration and table names */
require_once ($_SERWEB["configdir"] . "config_data_layer.php");

/** require default values for domain depending options */
require_once ($_SERWEB["configdir"] . "config_domain_defaults.php");

/** require other configuration */
require_once ($_SERWEB["configdir"] . "config.php");

/** if config.developer is present, replace default config by developer config */
if (file_exists($_SERWEB["configdir"] . "config.developer.php")){
	require_once ($_SERWEB["configdir"] . "config.developer.php");
}

/** require PEAR DB */
require_once 'DB.php';

/** require PEAR XML_RPC class */
require_once 'XML/RPC.php';
require_once ($_SERWEB["functionsdir"] . "xml_rpc_patch.php");

/** create log instance */
if ($config->enable_logging){
	require_once 'Log.php';
	eval('$serwebLog  = &Log::singleton("file", $config->log_file, "serweb", array(), '.$config->log_level.');');
}
else{
	$serwebLog  = NULL;

	/* 
	 * define constants used by logging to avoid errors reported by php
	 */
	 
	/** System is unusable */
	define('PEAR_LOG_EMERG',    0);
	/** Immediate action required */     
	define('PEAR_LOG_ALERT',    1);
	/** Critical conditions */
	define('PEAR_LOG_CRIT',     2);
	/** Error conditions */
	define('PEAR_LOG_ERR',      3);
	/** Warning conditions */
	define('PEAR_LOG_WARNING',  4);
	/** Normal but significant */
	define('PEAR_LOG_NOTICE',   5);
	/** Informational */
	define('PEAR_LOG_INFO',     6);
	/** Debug-level messages */
	define('PEAR_LOG_DEBUG',    7);
}

/** require functions */
require_once ($_SERWEB["functionsdir"] . "functions.php");

/** require Smarty and create Smarty instance */
require($_SERWEB["smartydir"]."smarty_serweb.php");
$smarty = new Smarty_Serweb;

/** require data layer for work with data store and create instance of it */
require_once ($_SERWEB["functionsdir"] . "data_layer.php");

/** require modules */
require_once ($_SERWEB["functionsdir"] . "load_modules.php");


/* 
 *  create instance of data_layer binded to proxy where is stored account  
 *  of currently authenticated user 
 */
$data_auth = CData_Layer::singleton("auth_user", $errors);
/*  reference $data to $data_auth */
$data = &$data_auth;


/* include domain depending config and activate it */
$domain_config=new CDomain_config();
$domain_config->activate_domain_config();
unset($domain_config);


/** require page layout */
require_once ($_SERWEB["functionsdir"] . "page.php");

?>

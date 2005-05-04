<?
/*
 * $Id: main_prepend.php,v 1.10 2005/05/04 15:33:10 kozlik Exp $
 */ 

//require class defintions
require_once ($_SERWEB["serwebdir"] . "class_definitions.php");

//require paths configuration
require_once ($_SERWEB["serwebdir"] . "../config/config_paths.php");

//set $config->domain
require_once ($_SERWEB["serwebdir"] . "../config/set_domain.php");

//require domain depending config
require_once ($_SERWEB["serwebdir"] . "config_domain.php");
$domain_config=new CDomain_config();

//require sql access configuration and table names
require_once ($_SERWEB["serwebdir"] . "../config/config_data_layer.php");

//require default values for domain depending options
require_once ($_SERWEB["serwebdir"] . "../config/config_domain_defaults.php");

//require other configuration
require_once ($_SERWEB["serwebdir"] . "../config/config.php");

//if config.developer is present, replace default config by developer config
if (file_exists($_SERWEB["serwebdir"] . "../config/config.developer.php")){
	require_once ($_SERWEB["serwebdir"] . "../config/config.developer.php");
}

//activate domain depending config
$domain_config->activate_domain_config();
unset($domain_config);

//require PEAR DB
require_once 'DB.php';

//require PEAR XML_RPC class
require_once 'XML/RPC.php';
require_once ($_SERWEB["serwebdir"] . "xml_rpc_patch.php");

//create log instance
if ($config->enable_loging){
	require_once 'Log.php';
	eval('$serwebLog  = &Log::singleton("file", $config->log_file, "serweb", array(), '.$config->log_level.');');
}
else{
	$serwebLog  = NULL;
}

//require Smarty and create Smarty instance
require($_SERWEB["serwebdir"]."../smarty/smarty_serweb.php");
$smarty = new Smarty_Serweb;


//require functions
require_once ($_SERWEB["serwebdir"] . "functions.php");

//require data layer for work with data store and create instance of it
require_once ($_SERWEB["serwebdir"] . "data_layer.php");

//require modules
require_once ($_SERWEB["serwebdir"] . "load_modules.php");


// create instance of data_layer binded to proxy where is stored account 
// of currently authenticated user
$data_auth = CData_Layer::singleton("auth_user", $errors);
// reference $data to $data_auth
$data = &$data_auth;

//require page layout
require_once ($_SERWEB["serwebdir"] . "page.php");

?>

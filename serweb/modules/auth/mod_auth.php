<?php
/*
 * $Id: mod_auth.php,v 1.2 2006/04/03 14:56:04 kozlik Exp $
 */ 


/*
 *	Configuration variables for this module
 *	
 *	$config->auth['backend'] = 'db';	allowed values: "db", "radius", "ldap"
 *		notice: "radius" and "ldap"	are experimental
 *	
 */


/*
 *	If config variables are not set, initialize them to default values
 */

 
/**
 *	Backend used for authentication
 *	Currently supported values: 'db', 'ldap', 'radius'
 */
if (!isset ($config->auth['backend'])) 
	$config->auth['backend'] = 'db';



/**
 *	Include backend
 */
include_module('auth_'.$config->auth['backend']);


/**
 *	Function called on beginning of script execution
 */ 
function auth_init(){
	
}
  
?>

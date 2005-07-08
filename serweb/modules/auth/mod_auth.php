<?php
/*
 * $Id: mod_auth.php,v 1.1 2005/07/08 11:06:52 kozlik Exp $
 */ 

/*
 *	If config variables are not set, initialize them to default values
 */

$config->auth['backend'] = 'db';

 
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

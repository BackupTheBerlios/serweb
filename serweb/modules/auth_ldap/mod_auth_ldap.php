<?php
/*
 * $Id: mod_auth_ldap.php,v 1.1 2005/07/08 11:08:36 kozlik Exp $
 */ 

/*
 *	If config variables are not set, initialize them to default values
 */

 
/**
 *	Distinguished Name where subscribers are stored
 */
if (!isset ($config->auth_ldap['dn'])) 
	$config->auth_ldap['dn'] = 'ou=subscribers,'.$config->data_ldap->base_dn;

/**
 *	Name of attribute where username is stored
 *	(in format username@domain)
 */
if (!isset ($config->auth_ldap['attrib']['user'])) 
	$config->auth_ldap['attrib']['user'] = 'user';

/**
 *	Name of attribute where uuid of user is stored
 */
if (!isset ($config->auth_ldap['attrib']['uuid'])) 
	$config->auth_ldap['attrib']['uuid'] = 'uuid';

/**
 *	Name of attribute where password of user is stored
 */
if (!isset ($config->auth_ldap['attrib']['pass'])) 
	$config->auth_ldap['attrib']['pass'] = 'password';


/**
 *	Function called on beginning of script execution
 */ 
function auth_ldap_init(){
	
}
  
?>

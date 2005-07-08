<?php
/*
 * $Id: mod_auth_radius.php,v 1.1 2005/07/08 11:08:36 kozlik Exp $
 */ 

/*
 *	If config variables are not set, initialize them to default values
 */

 
/**
 *	list of RADIUS servers for requests
 *
 *	At most 10 servers may be specified.	When multiple servers 
 *	are given, they are tried in round-robin fashion until a 
 *	valid response is received
 */

if (!isset ($config->auth_radius['host'])){
	$config->auth_radius['host'][0]['host'] = 'localhost';			//radius host
	$config->auth_radius['host'][0]['port'] = 1812;					//radius port
	$config->auth_radius['host'][0]['sharedsecret'] = "testing123";	//Shared secret
	$config->auth_radius['host'][0]['timeout']  = 3;				//Timeout for each request
	$config->auth_radius['host'][0]['maxtries'] = 3;				//Max. retries for each request
}



/**
 *	Function called on beginning of script execution
 */ 
function auth_radius_init(){
	
}
  
?>

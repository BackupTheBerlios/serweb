<?php
/**
 * Cron job maintenances database - should be run after midnight
 *
 * $Id: daily.php,v 1.1 2005/11/08 15:43:14 kozlik Exp $
 */

$_data_layer_required_methods=array('get_domains', 'get_users', 'mark_user_deleted', 
									'delete_sip_user', 'delete_domain', 
									'delete_pending_users', 'delete_acc');

$_required_modules = array('multidomain', 'subscribers', 'accounting');

require "prepend.php";

/* Get current time and store it. It is need to use the same time in all sql queries */
$GLOBALS['now'] = time();

/**
 *	Clean config directory of domain
 *
 *	@param array $errors	error messages
 *	@return bool			TRUE on success, FALSE on failure
 */
function clean_domain_config(&$errors){
	global $config, $data;

	$opt = array('deleted_before' => $GLOBALS['now'] - ($config->keep_deleted_interval * 86400) , 
	             'get_domain_names' => true, 
				 'return_all' => true);

	if (false === $deleted_domains = $data->get_domains($opt, $errors)) return false;
	
	foreach($deleted_domains as $dom){
		foreach($dom['names'] as $k => $v){
			/* remove symlinks for domain */
			if (false === domain_remove_symlinks($v['name'], $errors)) return false;
		}
		/* remove domain config directory */
		if  (false === remove_domain_config_dir($dom['id'], $errors)) return false;
	}

	return true;
}

/**
 *	Purge deleted users
 *
 *	@param array $errors	error messages
 *	@return bool			TRUE on success, FALSE on failure
 */
function purge_deleted_users(&$errors){
	global $config, $data;

	$opt = array('deleted_before' => $GLOBALS['now'] - ($config->keep_deleted_interval * 86400) , 
	             'get_user_aliases' => false, 
				 'return_all' => true);

	if (false === $deleted_users = $data->get_users(array(), $opt, $errors)) return false;

	foreach($deleted_users as $v){
		if (false === $data->delete_sip_user($v['serweb_auth'], $errors)) return false;
	}

}


/**
 *	Purge deleted domains
 *
 *	@param array $errors	error messages
 *	@return bool			TRUE on success, FALSE on failure
 */
function purge_deleted_domains(&$errors){
	global $config, $data;

	$opt = array('deleted_before' => $GLOBALS['now'] - ($config->keep_deleted_interval * 86400) , 
	             'get_domain_names' => false, 
				 'return_all' => true);

	if (false === $deleted_domains = $data->get_domains($opt, $errors)) return false;
	
	foreach($deleted_domains as $dom){
		if (false === $data->delete_domain($dom['id'], null, $errors)) return false;
	}
	return true;
}


/**
 *	Purge non-confirmed registrations
 *
 *	@param array $errors	error messages
 *	@return bool			TRUE on success, FALSE on failure
 */
function purge_pending_users(&$errors){
	global $config, $data;
	if (false === $data->delete_pending_users(null, $errors)) return false;
	return true;
}


/**
 *	Purge old acc record
 *
 *	@param array $errors	error messages
 *	@return bool			TRUE on success, FALSE on failure
 */
function purge_acc(&$errors){
	global $config, $data;
	if (false === $data->delete_acc(null, $errors)) return false;
	return true;
}


function main(&$errors){

	if (false === purge_deleted_users($errors)) return false;
	if (false === clean_domain_config($errors)) return false;
	if (false === purge_deleted_domains($errors)) return false;
	if (false === purge_pending_users($errors)) return false;
	if (false === purge_acc($errors)) return false;

	return true;
}

$errors = array();
main($errors);

if (is_array($errors) and count($errors)) {
	foreach($errors as $val) sw_log("cron job: daily maintenance - ".$val, PEAR_LOG_ERR);
	echo "There were errors during scripts run. See log file for details.\n";
	exit (1);
}

exit (0);

?>
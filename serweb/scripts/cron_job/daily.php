<?php
/**
 * Cron job maintenances database - should be run after midnight
 *
 * $Id: daily.php,v 1.2 2005/12/22 13:07:22 kozlik Exp $
 */

$_data_layer_required_methods=array('get_deleted_domains', 'get_deleted_users',  
									'get_domains', 'delete_sip_user', 'delete_domain', 
									'delete_acc');

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

	$opt = array('deleted_before' => $GLOBALS['now'] - ($config->keep_deleted_interval * 86400));

	if (false === $deleted_dids = $data->get_deleted_domains($opt)) return false;


	$opt = array('get_domain_names' => true, 
				 'return_all' => true,
				 'only_domains' => $deleted_dids,
				 'check_deleted_flag' => false);

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
 *	@return bool			TRUE on success, FALSE on failure
 */
function purge_deleted_users(){
	global $config, $data;

	$opt = array('deleted_before' => $GLOBALS['now'] - ($config->keep_deleted_interval * 86400));

	if (false === $deleted_users = $data->get_deleted_users($opt)) return false;

	foreach($deleted_users as $v){
		if (false === $data->delete_sip_user($v)) return false;
	}

}


/**
 *	Purge deleted domains
 *
 *	@return bool			TRUE on success, FALSE on failure
 */
function purge_deleted_domains(){
	global $config, $data;

	$opt = array('deleted_before' => $GLOBALS['now'] - ($config->keep_deleted_interval * 86400));

	if (false === $deleted_domains = $data->get_deleted_domains($opt)) return false;
	
	foreach($deleted_domains as $dom){
		if (false === $data->delete_domain($dom, null)) return false;
	}
	return true;
}


/**
 *	Purge non-confirmed registrations
 *
 *	@return bool			TRUE on success, FALSE on failure
 */
function purge_pending_users(){
	global $config, $data;

	$an = &$config->attr_names;
	$pending_ts = $GLOBALS['now'] - ($config->keep_pending_interval * 3600);

	/* get IDs of pending users */
	$o = array("name" => $an['pending_ts']);
	if (false === $attrs = $data->get_attr_by_val('user', $o)) return false;


	foreach ($attrs as $v){
		if ((int)$v['value'] < (int)$pending_ts) {
			if (false === $data->delete_sip_user($v['id'])) return false;
		}
	}

	return true;
}


/**
 *	Purge old acc record
 *
 *	@return bool			TRUE on success, FALSE on failure
 */
function purge_acc(){
	global $config, $data;
	if (false === $data->delete_acc(null)) return false;
	return true;
}


function main(&$errors){

	if (false === purge_deleted_users()) return false;
	if (false === clean_domain_config($errors)) return false;
	if (false === purge_deleted_domains()) return false;
	if (false === purge_pending_users()) return false;
	if (false === purge_acc()) return false;

	return true;
}

$errors = array();

$eh = &ErrorHandler::singleton();
$eh->set_errors_ref($errors);


main($errors);

$errors = &$eh->get_errors_array();

if (is_array($errors) and count($errors)) {
	foreach($errors as $val) sw_log("cron job: daily maintenance - ".$val, PEAR_LOG_ERR);
	echo "There were errors during scripts run. See log file for details.\n";
	exit (1);
}

exit (0);

?>

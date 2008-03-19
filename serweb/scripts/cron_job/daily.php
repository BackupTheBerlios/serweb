<?php
/**
 * Cron job maintenances database - should be run after midnight
 *
 * $Id: daily.php,v 1.5 2008/03/19 12:04:12 kozlik Exp $
 */

$_data_layer_required_methods=array('get_deleted_domains', 'get_deleted_users',  
									'get_domains', 'delete_sip_user', 'delete_domain', 
									'delete_acc', 'get_missed_calls_of_yesterday', 
									'get_users');

$_required_modules = array('multidomain', 'subscribers', 'accounting');

require "prepend.php";
require "missed_calls.php";

/* Get current time and store it. It is need to use the same time in all sql queries */
$GLOBALS['now'] = time();

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
        $dm_h = &DomainManipulator::singleton($dom);
        if (false === $dm_h->purge_domain()) return false;
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
 *	Purge non-confirmed domain registrations
 *
 *	@return bool			TRUE on success, FALSE on failure
 */
function purge_pending_domains(){
	global $config, $data;

	$an = &$config->attr_names;
	$pending_ts = $GLOBALS['now'] - ($config->keep_pending_interval * 3600);

	/* get IDs of pending users */
	$o = array("name" => $an['pending_ts']);
	if (false === $attrs = $data->get_attr_by_val('domain', $o)) return false;


	foreach ($attrs as $v){
		if ((int)$v['value'] < (int)$pending_ts) {
			if (false === $data->delete_domain($v['id'], null)) return false;
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
	if (false === purge_deleted_domains()) return false;
	if (false === purge_pending_users()) return false;
	if (false === purge_pending_domains()) return false;
	if (false === purge_acc()) return false;
	if (false === send_missed_calls()) return false;

	return true;
}

$errors = array();

$eh = &ErrorHandler::singleton();
$eh->set_errors_ref($errors);


main($errors);

$errors = &$eh->get_errors_array();

if (is_array($errors) and count($errors)) {
	echo "There were errors during scripts run.\n";
	foreach($errors as $val) {
		fwrite(STDERR, $val."\n");
		sw_log("cron job: daily maintenance - ".$val, PEAR_LOG_ERR);
	}
	exit (1);
}

exit (0);

?>

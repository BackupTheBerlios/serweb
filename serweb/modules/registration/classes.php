<?php
/*
 * $Id: classes.php,v 1.1 2006/09/18 13:07:26 kozlik Exp $
 */

class Registration{

	/**
	 *	Generate UID for new subscriber
	 *
	 *	@static
	 */
	function get_uid($username, $realm){
		global $config, $data;
		
		$an = &$config->attr_names;

		$data->add_method('get_new_user_id');
		$data->add_method('does_uid_exists');


		$ga = &Global_attrs::singleton();
		if (false === $format = $ga->get_attribute($an['uid_format'])) return false;
		
		
		switch ($format){
		/* numeric UID */
		case 1:
			if (false === $uid = $data->get_new_user_id(null)) return false;
			break;
			
		/* UUID by rfc4122 */
		case 2:
			$uid = rfc4122_uuid();
			/* check if uid doesn't exists */
			if (0 > ($exists = $data->does_uid_exists($uid, null))) return false;
			
			while ($exists){
				$uid = rfc4122_uuid();
				if (0 > ($exists = $data->does_uid_exists($uid, null))) return false;
			}
			break;

		/* UID in format 'username@realm' */
		case 0:
		default:  /* if format of UIDs is not set, assume the first choice */
			$uid = $username."@".$realm;
			/* check if uid doesn't exists */
			if (0 > ($exists = $data->does_uid_exists($uid, null))) return false;
			
			$i = 0;
			while ($exists){
				$uid = $username."@".$realm."_".$i++;
				if (0 > ($exists = $data->does_uid_exists($uid, null))) return false;
			}
			
			break;
		}
		
		return $uid;
	}


	/**
	 *	Create new subscriber
	 *
	 *	Create credentials, uris and user_attrs
	 *
	 *	Options:
	 *		- 'disabled' - create the subscriber disabled
	 *	
	 *	
	 *	@param	string	$username
	 *	@param	string	$did
	 *	@param	string	$password
	 *	@param	array	$attrs
	 *	@param	array	$opts
	 *	@return	bool
	 *	@static
	 */
	function add_subscriber($username, $did, $password, $attrs, &$opts){
		global $config, $data;
	
		$an = &$config->attr_names;

		$data->add_method('add_credentials');
		$data->add_method('add_uri');

		$o_disabled = isset($opts['disabled']) ? (bool)$opts['disabled'] : false;


		/* get realm */
		$opt=array("did"=>$did);
		if (false === $realm = Attributes::get_attribute($an['digest_realm'], $opt)) return false;
		$opts['realm'] = $realm;

		/* generate uid */
		if (false === $uid = Registration::get_uid($username, $realm)) return false;
		$opts['uid'] = $uid;

		if (false === $data->transaction_start()) return false;

		/* store credentials */
		$o = array('disabled' => $o_disabled);
		if (false === $data->add_credentials($uid, $did, $username, $realm, $password, $o)) {
			$data->transaction_rollback();
			return false;
		}

		/* store uri */
		$o = array('disabled' => $o_disabled,
		           'canon' => true);
		if (false === $data->add_uri($uid, $username, $did, $o)) {
			$data->transaction_rollback();
			return false;
		}

		/* store attributes */
		$ua = &User_Attrs::singleton($uid);
		foreach($attrs as $k => $v){
			if (false === $ua->set_attribute($k, $v)) {
				$data->transaction_rollback();
				return false;
			}
		}
	
		if (false === $data->transaction_commit()) return false;

		return true;	
	}

}
?>

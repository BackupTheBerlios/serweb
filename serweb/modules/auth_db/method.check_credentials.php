<?php
/*
 * $Id: method.check_credentials.php,v 1.2 2005/12/22 13:15:53 kozlik Exp $
 */

class CData_Layer_check_credentials {
	var $required_methods = array();
	
	/**
	 *  Check given credentials and return uid of user (string) if they are 
	 *	correct. If credentials are wrong integer error code is returned:
	 *		 0 - credentials can not be checked (db error)
	 *		-1 - this tripple (uname, realm, password) not exists
	 *		-2 - this credentials is not for use in serweb
	 *		-3 - account is disabled
	 *		-4 - account pending for confirmation
	 *		-5 - account is deleted
	 *
	 *  Possible options:
	 *    hash	(string)     default: "clear"
	 *      determine hash function by which password is hashed. 
	 *		Possible values are:
	 *		- 'clear'
	 *		- 'ha1'
	 *		- 'ha1b'
	 *
	 *	@param string $uname	username
	 *	@param string $realm	realm
	 *	@param string $passw	password
	 *	@param array $opt		associative array of options
	 *	@return mixed			uid or error code
	 */ 
	function check_credentials($uname, $realm, $passw, $opt){
		global $config, $serweb_auth, $sess;

		$errors = array();

		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table name */
		$t_name = &$config->data_sql->credentials->table_name;
		/* col names */
		$c = &$config->data_sql->credentials->cols;
		/* flags */
		$f = &$config->data_sql->credentials->flag_values;
		
		/* set default values for options */
		$opt_hash = isset($opt["hash"]) ? $opt["hash"] : "clear";
		
		
		/* prepare SQL query */
		$q="select c.".$c->uid.", c.".$c->flags.
		    " from ". $t_name." c ".
			" where c.".$c->uname."='".addslashes($uname)."' and c.".$c->realm."='".addslashes($realm)."'";
			
		if     ($opt_hash == "clear") $q .= " and c.".$c->password."='".addslashes($passw)."'";
		elseif ($opt_hash == "ha1")   $q .= " and c.".$c->ha1."='".addslashes($passw)."'";
		elseif ($opt_hash == "ha1b")  $q .= " and c.".$c->ha1b."='".addslashes($passw)."'";
		else   {
			sw_log("Invalid hash method: '".$opt_hash."'", PEAR_LOG_CRIT);
			return 0;
		}

		$res=$this->db->query($q);
		if (DB::isError($res)) { 
			log_errors($res, $errors); 
			ErrorHandler::add_error($errors);
			return 0; 
		}

		/* account not exists or password is wrong */
		if (!$res->numRows()) {
			return -1;
		}
		
		$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
		$res->free();

		/* check flags */
		if (! ($row[$c->flags] & $f["DB_FOR_SERWEB"])){
			sw_log("Account '".$uname."@".$realm."' is not marked for use in serweb", PEAR_LOG_INFO);
			return -2;
		}

		if ( $row[$c->flags] & $f["DB_DISABLED"]){
			sw_log("Account '".$uname."@".$realm."' is disabled", PEAR_LOG_INFO);
			return -3;
		}

		if ( $row[$c->flags] & $f["DB_PENDING"]){
			sw_log("Account '".$uname."@".$realm."' pending for confirmation", PEAR_LOG_INFO);
			return -4;
		}

		if ( $row[$c->flags] & $f["DB_DELETED"]){
			sw_log("Account '".$uname."@".$realm."' is marked as deleted", PEAR_LOG_INFO);
			return -5;
		}


		return $row[$c->uid];
	}
}
?>

<?php
/*
 * $Id: method.enable_user.php,v 1.1 2005/12/22 12:45:00 kozlik Exp $
 */

class CData_Layer_enable_user {
	var $required_methods = array();
	
	/**
	 *  Enable or disable user account
	 *
	 *  Possible options:
	 *
	 *    uid		(string)   	default: null
	 *      uid of user which should be enabled/disabled
	 *		this option is required
	 *      
	 *    disable	(bool)  	default: false
	 *      if true user will be disabled, otherwise wil be enabled
	 *      
	 *	@param array $opt		associative array of options
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function enable_user($opt){
		global $config, $data;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$tc_name = &$config->data_sql->credentials->table_name;
		$ta_name = &$config->data_sql->user_attrs->table_name;
		/* col names */
		$cc = &$config->data_sql->credentials->cols;
		$ca = &$config->data_sql->user_attrs->cols;
		/* flags */
		$fc = &$config->data_sql->credentials->flag_values;
		$fa = &$config->data_sql->user_attrs->flag_values;

		$an = &$config->attr_names;

	    $o_uid = (isset($opt['uid'])) ? $opt['uid'] : null;
	    $o_disable = (isset($opt['disable'])) ? $opt['disable'] : false;

		if (is_null($o_uid)) {
			ErrorHandler::log_errors(PEAR::raiseError('subscriber which should be enabled or disabled is not specified')); 
			return false;
		}
		
		if (false === $this->transaction_start()) return false;

		$q = "update ".$tc_name." set ";
		if ($o_disable)	$q .= $cc->flags." = ".$cc->flags." | ".$fc['DB_DISABLED'];
		else            $q .= $cc->flags." = ".$cc->flags." & ~".$fc['DB_DISABLED'];
		$q .= " where ".$cc->uid." = '".$o_uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res);
			$this->transaction_rollback();
			return false;
		}


		$q = "update ".$ta_name." set ";
		if ($o_disable) $q .= $ca->flags." = ".$ca->flags." | ".$fa['DB_DISABLED'];
		else            $q .= $ca->flags." = ".$ca->flags." & ~".$fa['DB_DISABLED'];
		$q .= " where ".$ca->uid." = '".$o_uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res);
			$this->transaction_rollback();
			return false;
		}

		if (!$o_disable){
			/*
			 *	Unset attributes of pending users if they are set
			 */
			$user_attrs = &User_Attrs::singleton($o_uid);
			if (false === $user_attrs->unset_attribute($an['confirmation'])) {$this->transaction_rollback(); return false;}
			if (false === $user_attrs->unset_attribute($an['pending_ts'])) {$this->transaction_rollback(); return false;}
		}

		if (false === $this->transaction_commit()) return false;

		return true;
	}
	
}

?>

<?php
/*
 * $Id: method.mark_user_deleted.php,v 1.3 2005/12/22 12:45:00 kozlik Exp $
 */

class CData_Layer_mark_user_deleted {
	var $required_methods = array();
	
	/**
	 *  Mark user account as deleted
	 *
	 *  Possible options:
	 *
	 *    uid			(string)	default: null
	 *      uid of user which should be enabled/disabled
	 *		this option is required
	 *      
	 *	  delete_asap 	(bool)			defult: false
	 *      if is true, user will be deleted as soon as possible (on next
	 *		cleaning of database)
	 *      
	 *	@param array $opt		associative array of options
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function mark_user_deleted($opt){
		global $config, $data;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$tc_name = &$config->data_sql->credentials->table_name;
		$ta_name = &$config->data_sql->user_attrs->table_name;
		$tu_name = &$config->data_sql->uri->table_name;
		/* col names */
		$cc = &$config->data_sql->credentials->cols;
		$ca = &$config->data_sql->user_attrs->cols;
		$cu = &$config->data_sql->uri->cols;
		/* flags */
		$fc = &$config->data_sql->credentials->flag_values;
		$fa = &$config->data_sql->user_attrs->flag_values;
		$fu = &$config->data_sql->uri->flag_values;

		$an = &$config->attr_names;
		
	    $o_uid     = (isset($opt['uid'])) ? $opt['uid'] : null;
	    $o_del_asap = (isset($opt['delete_asap'])) ? (bool)$opt['delete_asap'] : false;

		if (is_null($o_uid)) {
			ErrorHandler::log_errors(PEAR::raiseError('subscriber which should be marked as deleted is not specified')); 
			return false;
		}

		if (false === $this->transaction_start()) return false;


		$val = $o_del_asap ? 1 : time();
		$user_attrs = &User_Attrs::singleton($o_uid);
		if (false === $user_attrs->set_attribute($an['deleted_ts'], time())) {
			$this->transaction_rollback();
			return false;
		}

		$q = "update ".$tc_name." 
		      set ".$cc->flags." = ".$cc->flags." | ".$fc['DB_DELETED']." 
			  where ".$cc->uid." = '".$o_uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res);
			$this->transaction_rollback();
			return false;
		}


		$q = "update ".$ta_name." 
		      set ".$ca->flags." = ".$ca->flags." | ".$fa['DB_DELETED']." 
			  where ".$ca->uid." = '".$o_uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res);
			$this->transaction_rollback();
			return false;
		}


		$q = "update ".$tu_name." 
		      set ".$cu->flags." = ".$cu->flags." | ".$fu['DB_DELETED']." 
			  where ".$cu->uid." = '".$o_uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res);
			$this->transaction_rollback();
			return false;
		}

		if (false === $this->transaction_commit()) return false;

		return true;
	}
}

?>

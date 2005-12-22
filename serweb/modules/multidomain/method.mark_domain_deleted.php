<?php
/*
 * $Id: method.mark_domain_deleted.php,v 1.2 2005/12/22 12:38:54 kozlik Exp $
 */

class CData_Layer_mark_domain_deleted {
	var $required_methods = array();
	
	/**
	 *  Mark domain as deleted
	 *
	 *  Possible options:
	 *
	 *    did		(int)   	default: null
	 *      id of domain which will be deleted
	 *      REQUIRED
	 *      
	 *	@param array $opt		associative array of options
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function mark_domain_deleted($opt){
		global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$td_name = &$config->data_sql->domain->table_name;
		$ta_name = &$config->data_sql->domain_attrs->table_name;
		$tu_name = &$config->data_sql->uri->table_name;
		/* col names */
		$cd = &$config->data_sql->domain->cols;
		$ca = &$config->data_sql->domain_attrs->cols;
		$cu = &$config->data_sql->uri->cols;
		/* flags */
		$fd = &$config->data_sql->domain->flag_values;
		$fa = &$config->data_sql->domain_attrs->flag_values;
		$fu = &$config->data_sql->uri->flag_values;

		$an = &$config->attr_names;

	    $o_did = (isset($opt['did'])) ? $opt['did'] : null;

		if (is_null($o_did)) {
			ErrorHandler::log_errors(PEAR::raiseError('domain for mark as deleted is not specified')); 
			return false;
		}

		if (false === $this->transaction_start()) return false;

		$domain_attrs = &Domain_Attrs::singleton($o_did);
		if (false === $domain_attrs->set_attribute($an['deleted_ts'], time())) {
			$this->transaction_rollback();
			return false;
		}


		$q = "update ".$td_name." 
		      set ".$cd->flags." = ".$cd->flags." | ".$fd['DB_DELETED']."
			  where ".$cd->did." = '".$o_did."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res); 
			$this->transaction_rollback();
			return false;
		}

		$q = "update ".$ta_name." 
		      set ".$ca->flags." = ".$ca->flags." | ".$fa['DB_DELETED']."
			  where ".$ca->did." = '".$o_did."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res); 
			$this->transaction_rollback();
			return false;
		}


		$q = "update ".$tu_name." 
		      set ".$cu->flags." = ".$cu->flags." | ".$fu['DB_DELETED']." 
			  where ".$cu->did." = '".$o_did."'";

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

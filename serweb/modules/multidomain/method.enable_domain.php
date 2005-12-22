<?php
/*
 * $Id: method.enable_domain.php,v 1.3 2005/12/22 12:38:54 kozlik Exp $
 */

class CData_Layer_enable_domain {
	var $required_methods = array();
	
	/**
	 *  Enable or disable domain
	 *
	 *  Possible options:
	 *
	 *    did		(int)   	default: null
	 *      id of domain which will be en/disabled
	 *      this option is REQUIRED
	 *      
	 *    disable	(bool)  	default: false
	 *      if true domain will be disabled, otherwise wil be enabled
	 *      
	 *	@param array $opt		associative array of options
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function enable_domain($opt){
		global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$td_name = &$config->data_sql->domain->table_name;
		$ta_name = &$config->data_sql->domain_attrs->table_name;
		/* col names */
		$cd = &$config->data_sql->domain->cols;
		$ca = &$config->data_sql->domain_attrs->cols;
		/* flags */
		$fd = &$config->data_sql->domain->flag_values;
		$fa = &$config->data_sql->domain_attrs->flag_values;

	    $o_did = (isset($opt['did'])) ? $opt['did'] : null;
	    $o_disable = (isset($opt['disable'])) ? $opt['disable'] : false;

		if (is_null($o_did)) {
			ErrorHandler::log_errors(PEAR::raiseError('domain for en/disable is not specified')); 
			return false;
		}

		if (false === $this->transaction_start()) return false;

		$q = "update ".$td_name." set ";
		if ($o_disable)	$q .= $cd->flags." = ".$cd->flags." | ".$fd['DB_DISABLED'];
		else            $q .= $cd->flags." = ".$cd->flags." & ~".$fd['DB_DISABLED'];
		$q .= " where ".$cd->did." = '".$o_did."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			ErrorHandler::log_errors($res);
			$this->transaction_rollback();
			return false;
		}

		$q = "update ".$ta_name." set ";
		if ($o_disable) $q .= $ca->flags." = ".$ca->flags." | ".$fa['DB_DISABLED'];
		else            $q .= $ca->flags." = ".$ca->flags." & ~".$fa['DB_DISABLED'];
		$q .= " where ".$ca->did." = '".$o_did."'";

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

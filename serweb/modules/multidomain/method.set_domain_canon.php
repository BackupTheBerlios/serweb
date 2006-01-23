<?php
/*
 * $Id: method.set_domain_canon.php,v 1.1 2006/01/23 15:23:17 kozlik Exp $
 */

class CData_Layer_set_domain_canon {
	var $required_methods = array();

	/**
	 *  Set domain name canonical
	 *
	 *	If there is an other canonical domain name is unset
	 *
	 *  Possible options parameters:
	 *		none
	 *
	 *	@param string $did		domain ID
	 *	@param string $domname	domain name
	 *	@param array $opt		associative array of options
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function set_domain_canon($did, $domname, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$td_name = &$config->data_sql->domain->table_name;
		/* col names */
		$cd = &$config->data_sql->domain->cols;
		/* flags */
		$fd = &$config->data_sql->domain->flag_values;

		if (false === $this->transaction_start()) return false;

		$q="update ".$td_name." 
			set ".$cd->flags."=(".$cd->flags." & (~".$fd['DB_CANON']."))
			where ".$cd->did."='".$did."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			$this->transaction_rollback();
			ErrorHandler::log_errors($res); return false;
		}

		$q="update ".$td_name." 
			set ".$cd->flags."=(".$cd->flags." | ".$fd['DB_CANON'].")
			where ".$cd->did."='".$did."' and ".$cd->name."='".$domname."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			$this->transaction_rollback();
			ErrorHandler::log_errors($res); return false;
		}

		if (false === $this->transaction_commit()) return false;

		return true;
	}

}

?>

<?php
/*
 * $Id: method.delete_domain.php,v 1.5 2006/03/08 15:46:27 kozlik Exp $
 */

class CData_Layer_delete_domain {
	var $required_methods = array();

	/**
	 *  Delete domain
	 *
	 *  Possible options parameters:
	 *		none
	 *
	 *	@param string $did		domain ID
	 *	@param array $opt		associative array of options
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function delete_domain($did, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$ta_name = &$config->data_sql->domain_attrs->table_name;
		$td_name = &$config->data_sql->domain->table_name;
		/* col names */
		$ca = &$config->data_sql->domain_attrs->cols;
		$cd = &$config->data_sql->domain->cols;
		/* flags */
		$fa = &$config->data_sql->domain_attrs->flag_values;
		$fd = &$config->data_sql->domain->flag_values;

		$q="delete from ".$td_name." 
			where ".$cd->did."=".$this->sql_format($did, "s");

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			//expect that table mayn't exist in installed version
			if ($res->getCode() != DB_ERROR_NOSUCHTABLE) {
				ErrorHandler::log_errors($res); return false;
			} 
		}

		$q="delete from ".$ta_name." 
			where ".$ca->did."=".$this->sql_format($did, "s");

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			//expect that table mayn't exist in installed version
			if ($res->getCode() != DB_ERROR_NOSUCHTABLE) {
				ErrorHandler::log_errors($res); return false;
			} 
		}

		return true;
	}

}

?>

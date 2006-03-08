<?php
/*
 * $Id: method.delete_customer.php,v 1.3 2006/03/08 15:46:27 kozlik Exp $
 */

class CData_Layer_delete_customer {
	var $required_methods = array();

	/**
	 *  Delete customer 
	 *
	 *  Possible options parameters:
	 *    primary_key	(array) required
	 *      contain primary key of record which should be deleted
	 *      The array contain the same keys as functon get_customers returned in entry 'primary_key'
	 *
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function delete_customer($opt, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		/* table's name */
		$tc_name = &$config->data_sql->customers->table_name;
		/* col names */
		$cc = &$config->data_sql->customers->cols;

		if (!isset($opt['primary_key']) or 
			!is_array($opt['primary_key']) or 
			empty($opt['primary_key'])){
			log_errors(PEAR::raiseError('primary key is missing'), $errors); return false;
		}


		$q="delete from ".$tc_name." 
			where ".$cc->cid."=".$this->sql_format($opt['primary_key']['cid'], "n");

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			return false;
		}
		return true;

	}

}

?>

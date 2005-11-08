<?php
/*
 * $Id: method.delete_acc.php,v 1.1 2005/11/08 15:43:14 kozlik Exp $
 */

class CData_Layer_delete_acc {
	var $required_methods = array();

	/**
	 *  Purge old acc records
	 *
	 *  Possible options parameters:
	 *		none
	 *
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function delete_acc($opt, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="delete from ".$config->data_sql->table_accounting." 
			where DATE_ADD(timestamp, INTERVAL ".$config->keep_acc_interval." DAY) < now()";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			//expect that table mayn't exist in installed version
			if ($res->getCode() != DB_ERROR_NOSUCHTABLE) {
				log_errors($res, $errors); return false;
			} 
		}

		return true;
	}

}

?>

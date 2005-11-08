<?php
/*
 * $Id: method.delete_pending_users.php,v 1.1 2005/11/08 15:43:14 kozlik Exp $
 */

class CData_Layer_delete_pending_users {
	var $required_methods = array();

	/**
	 *  Delete pending users
	 *
	 *  Possible options parameters:
	 *		none
	 *
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function delete_pending_users($opt, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="delete from ".$config->data_sql->table_pending." 
			where DATE_ADD(datetime_created, INTERVAL ".$config->keep_pending_interval." HOUR) < now()";

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

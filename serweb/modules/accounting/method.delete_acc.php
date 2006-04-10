<?php
/*
 * $Id: method.delete_acc.php,v 1.3 2006/04/10 13:03:36 kozlik Exp $
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
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function delete_acc($opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return 0;
		}

		/* table's name */
		$t_name = &$config->data_sql->acc->table_name;
		/* col names */
		$c = &$config->data_sql->acc->cols;
		/* flags */
		$f = &$config->data_sql->acc->flag_values;

		if ($this->db_host['parsed']['phptype'] == 'mysql') {
			$q="delete from ".$t_name." 
				where DATE_ADD(".$c->request_timestamp.", INTERVAL ".$config->keep_acc_interval." DAY) < now()";
		}
		else{
			$q="delete from ".$t_name." 
				where (".$c->request_timestamp." + INTERVAL '".$config->keep_acc_interval." DAY') < now()";
		}

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

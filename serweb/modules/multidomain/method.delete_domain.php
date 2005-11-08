<?php
/*
 * $Id: method.delete_domain.php,v 1.3 2005/11/08 15:43:14 kozlik Exp $
 */

class CData_Layer_delete_domain {
	var $required_methods = array();

	/**
	 *  Delete domain
	 *
	 *  Possible options parameters:
	 *		none
	 *
	 *	@param string $d_id		domain ID
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function delete_domain($d_id, $opt, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$cd = &$config->data_sql->domain;
		$cp = &$config->data_sql->dom_pref;

		$q="delete from ".$config->data_sql->table_domain." 
			where ".$cd->id."='".$d_id."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			//expect that table mayn't exist in installed version
			if ($res->getCode() != DB_ERROR_NOSUCHTABLE) {
				log_errors($res, $errors); return false;
			} 
		}

		$q="delete from ".$config->data_sql->table_dom_preferences." 
			where ".$cp->id."='".$d_id."'";

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

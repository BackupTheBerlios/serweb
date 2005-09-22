<?php
/*
 * $Id: method.get_new_domain_id.php,v 1.1 2005/09/22 14:29:16 kozlik Exp $
 */

class CData_Layer_get_new_domain_id {
	var $required_methods = array();
	
	/**
	 *  return new id for a domain
	 *
	 *  Possible options:
	 *	 none
	 *      
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return int				new id or FALSE on error
	 */ 
	function get_new_domain_id($opt, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->domain;


		$q="select max(".$c->id.")
		    from ".$config->data_sql->table_domain;

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();

		return $row[0]+1;
	}
	
}
?>

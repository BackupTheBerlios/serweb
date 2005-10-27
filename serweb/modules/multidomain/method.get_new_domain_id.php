<?php
/*
 * $Id: method.get_new_domain_id.php,v 1.2 2005/10/27 08:40:41 kozlik Exp $
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

		$cd = &$config->data_sql->domain;
		$cp = &$config->data_sql->dom_pref;

		$q="select max(".$cd->id.")
		    from ".$config->data_sql->table_domain;

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row1=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();


		$q="select max(".$cp->id.")
		    from ".$config->data_sql->table_dom_preferences;

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row2=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();


		return max($row1[0], $row2[0]) + 1;
	}
	
}
?>

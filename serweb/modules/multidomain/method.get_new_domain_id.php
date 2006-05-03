<?php
/*
 * $Id: method.get_new_domain_id.php,v 1.4 2006/05/03 13:41:07 kozlik Exp $
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

		/* table's name */
		$td_name = &$config->data_sql->domain->table_name;
		$ta_name = &$config->data_sql->domain_attrs->table_name;
		/* col names */
		$cd = &$config->data_sql->domain->cols;
		$ca = &$config->data_sql->domain_attrs->cols;
		/* flags */
		$fd = &$config->data_sql->domain->flag_values;
		$fa = &$config->data_sql->domain_attrs->flag_values;


		$q="select max(".$this->get_sql_cast_to_int_funct($cd->did).")
		    from ".$td_name."
			where ".$this->get_sql_regex_match("^[0-9]+$", $cd->did, null);

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row1=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();


		$q="select max(".$this->get_sql_cast_to_int_funct($ca->did).")
		    from ".$ta_name."
			where ".$this->get_sql_regex_match("^[0-9]+$", $ca->did, null);

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row2=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();


		return max($row1[0], $row2[0]) + 1;
	}
	
}
?>

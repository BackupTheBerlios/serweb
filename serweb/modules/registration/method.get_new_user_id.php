<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_new_user_id.php,v 1.2 2007/02/14 16:46:31 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_registration
 */ 

/**
 *	Data layer container holding the method for generate new (numerical) UID
 * 
 *	@package    serweb
 *	@subpackage mod_registration
 */ 
class CData_Layer_get_new_user_id {
	var $required_methods = array();
	
	/**
	 *  return new id for a user
	 *
	 *  Possible options:
	 *	 - none
	 *      
	 *	@param array $opt		associative array of options
	 *	@return int				new id or FALSE on error
	 */ 
	function get_new_user_id($opt){
		global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$tc_name = &$config->data_sql->credentials->table_name;
		$ta_name = &$config->data_sql->user_attrs->table_name;
		/* col names */
		$cc = &$config->data_sql->credentials->cols;
		$ca = &$config->data_sql->user_attrs->cols;
		/* flags */
		$fc = &$config->data_sql->credentials->flag_values;
		$fa = &$config->data_sql->user_attrs->flag_values;


		$q="select max(".$this->get_sql_cast_to_int_funct($cc->uid).")
		    from ".$tc_name."
			where ".$this->get_sql_regex_match("^[0-9]+$", $cc->uid, null);

		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
		$row1=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();


		$q="select max(".$this->get_sql_cast_to_int_funct($ca->uid).")
		    from ".$ta_name."
			where ".$this->get_sql_regex_match("^[0-9]+$", $ca->uid, null);

		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
		$row2=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();


		return max($row1[0], $row2[0]) + 1;
	}
}
?>

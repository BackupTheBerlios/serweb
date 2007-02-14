<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.does_uid_exists.php,v 1.2 2007/02/14 16:46:31 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_registration
 */ 

/**
 *	Data layer container holding the method for check if the UID already exists
 * 
 *	@package    serweb
 *	@subpackage mod_registration
 */ 
class CData_Layer_does_uid_exists {
	var $required_methods = array();
	
	/**
	 *	
	 *  return:
	 *	 o  0 - uid doesn't exists
	 *	 o  1 - uid exists
	 *	 o -1 - on error
	 *
	 *  Possible options:
	 *	 - none
	 *	
	 *      
	 *	@param string $uid		
	 *	@param array $opt		associative array of options
	 *	@return int				
	 */ 
	function does_uid_exists($uid, $opt){
		global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return -1;
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


		$q="select count(*)
		    from ".$tc_name."
			where ".$cc->uid." = ".$this->sql_format($uid, 's');

		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return -1;}
		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();

		if ($row[0]) return 1;

		$q="select count(*)
		    from ".$ta_name."
			where ".$ca->uid." = ".$this->sql_format($uid, 's');

		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return -1;}
		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();

		if ($row[0]) return 1;

		return 0;
	}
}
?>

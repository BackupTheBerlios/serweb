<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.delete_uri_attrs.php,v 1.3 2007/02/14 16:46:31 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_uri
 */ 

/**
 *	Data layer container holding the method for delete uri attributes
 * 
 *	@package    serweb
 *	@subpackage mod_uri
 */ 
class CData_Layer_delete_uri_attrs {
	var $required_methods = array();

	/**
	 *  Delete all uri attributes
	 *
	 *  Possible options parameters:
	 *	 - none
	 *
	 *	@param string $username
	 *	@param string $did		
	 *	@param array $opt		associative array of options
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function delete_uri_attrs($scheme, $username, $did, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$ta_name = &$config->data_sql->uri_attrs->table_name;
		/* col names */
		$ca = &$config->data_sql->uri_attrs->cols;

		$q="delete from ".$ta_name." 
			where ".$ca->scheme."=".$this->sql_format($scheme, "s"). "and
			      ".$ca->username."=".$this->sql_format($username, "s"). "and
				  ".$ca->did."=".$this->sql_format($did, "s");

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

<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.del_attr_type.php,v 1.2 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 

/**
 *	Data layer container holding the method for delete attribute type
 * 
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 
class CData_Layer_del_attr_type {
	var $required_methods = array();
	
	/**
	 *  Delete attribute type
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *	 - none
	 *
	 *	@param	string		$name	Name of attribute to delete
	 *	@param	array		$opt	Array of options
	 *	@return bool
	 */ 
	 
	function del_attr_type($name, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$t_name = &$config->data_sql->attr_types->table_name;
		/* col names */
		$c = &$config->data_sql->attr_types->cols;

		$q = "delete from ".$t_name." 
		      where ".$c->name." = ".$this->sql_format($name,  "s");

		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>

<?php
/*
 * $Id: method.del_global_attr.php,v 1.2 2006/03/08 15:46:26 kozlik Exp $
 */

class CData_Layer_del_global_attr {
	var $required_methods = array();
	
	/**
	 *  Delete global attribute
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *		none
	 *
	 *	@return bool
	 */ 
	 
	function del_global_attr($name, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$t_name = &$config->data_sql->global_attrs->table_name;
		/* col names */
		$c = &$config->data_sql->global_attrs->cols;
		/* flags */
		$f = &$config->data_sql->global_attrs->flag_values;


		$q = "delete from ".$t_name." 
		      where ".$c->name." = ".$this->sql_format($name, "s");
		
		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>

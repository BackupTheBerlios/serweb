<?php
/*
 * $Id: method.del_uri_attr.php,v 1.1 2006/07/20 17:46:40 kozlik Exp $
 */

class CData_Layer_del_uri_attr {
	var $required_methods = array();
	
	/**
	 *  Delete uri attribute
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *		none
	 *
	 *	@return bool
	 */ 
	 
	function del_uri_attr($username, $did, $name, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors);
			return false;
		}

		/* table's name */
		$t_name = &$config->data_sql->uri_attrs->table_name;
		/* col names */
		$c = &$config->data_sql->uri_attrs->cols;
		/* flags */
		$f = &$config->data_sql->uri_attrs->flag_values;


		$q = "delete from ".$t_name." 
		      where ".$c->name."     = ".$this->sql_format($name,     "s")." and 
					".$c->username." = ".$this->sql_format($username, "s")." and 
					".$c->did."      = ".$this->sql_format($did,      "s");
		
		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>
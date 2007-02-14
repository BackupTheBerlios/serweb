<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.del_uri_attr.php,v 1.3 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 

/**
 *	Data layer container holding the method for delete uri attribute
 * 
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 
class CData_Layer_del_uri_attr {
	var $required_methods = array();
	
	/**
	 *  Delete uri attribute
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *	 - none
	 *
	 *	@return bool
	 */ 
	 
	function del_uri_attr($scheme, $username, $did, $name, $opt){
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
		      where ".$c->scheme."   = ".$this->sql_format($scheme,   "s")." and 
					".$c->name."     = ".$this->sql_format($name,     "s")." and 
					".$c->username." = ".$this->sql_format($username, "s")." and 
					".$c->did."      = ".$this->sql_format($did,      "s");
		
		$res=$this->db->query($q);
		if (DB::isError($res)) { ErrorHandler::log_errors($res); return false; }

		return true;
	}
}
?>

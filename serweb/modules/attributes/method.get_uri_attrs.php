<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_uri_attrs.php,v 1.4 2009/05/06 08:41:42 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 

/**
 *	Data layer container holding the method for get uri attributes
 * 
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 
class CData_Layer_get_uri_attrs {
	var $required_methods = array();
	
	/**
	 *  Get values of uri attributes
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *	 - none
	 *
	 *	@return array
	 */ 
	 
	function get_uri_attrs($scheme, $username, $did, $opt){
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

		$out = array();

		/*
		 *	get global_attrs
		 */
		$flags_val = $f['DB_FOR_SERWEB'];

		$q="select ".$c->name." as name,
		           ".$c->value." as value 
		    from ".$t_name."
			where ".$c->scheme."   = ".$this->sql_format($scheme,   "s")." and 
				  ".$c->username." = ".$this->sql_format($username, "s")." and 
				  ".$c->did."      = ".$this->sql_format($did,      "s")." and 
			      (".$c->flags." & ".$flags_val.") = ".$flags_val;

        if (isset($c->order)) $q .= " order by ".$c->name.", ".$c->order;
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			ErrorHandler::add_error($errors);
			return false;
		}


		$ats = &Attr_types::singleton();

		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			if (false === $at = &$ats->get_attr_type($row['name'])) return false;
			if (is_object($at) and $at->is_multivalue())
				$out[$row['name']][] =  $row['value'];
			else
				$out[$row['name']] =  $row['value'];
		}

		$res->free();

		return $out;
	}
}
?>

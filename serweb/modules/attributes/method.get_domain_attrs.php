<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_domain_attrs.php,v 1.3 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 

/**
 *	Data layer container holding the method for get domain attributes
 * 
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 
class CData_Layer_get_domain_attrs {
	var $required_methods = array();
	
	/**
	 *  Get values of global attributes
	 *
	 *	On error this method returning FALSE.
	 *
	 *  Possible options:
	 *	 - none
	 *
	 *	@return array
	 */ 
	 
	function get_domain_attrs($did, $opt){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		/* table's name */
		$t_name = &$config->data_sql->domain_attrs->table_name;
		/* col names */
		$c = &$config->data_sql->domain_attrs->cols;
		/* flags */
		$f = &$config->data_sql->domain_attrs->flag_values;

		$out = array();
		$errors = array();

		/*
		 *	get global_attrs
		 */
		$flags_val = $f['DB_FOR_SERWEB'];

		$q="select ".$c->name." as name,
		           ".$c->value." as value 
		    from ".$t_name."
			where  ".$c->did." = ".$this->sql_format($did, "s")." and 
			      (".$c->flags." & ".$flags_val.") = ".$flags_val;
		
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

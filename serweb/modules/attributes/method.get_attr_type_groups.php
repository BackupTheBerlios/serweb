<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_attr_type_groups.php,v 1.2 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 

/**
 *	Data layer container holding the method for get groups of attribute types
 * 
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 
class CData_Layer_get_attr_type_groups{
	var $required_methods = array();
	
	/**
	 * return list of all attributes without atribute named as $att_edit
	 * $att_edit may be null
	 */
	 
	function get_attr_type_groups($opt=null){
		global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		/* table's name */
		$t_at = &$config->data_sql->attr_types->table_name;
		/* col names */
		$c = &$config->data_sql->attr_types->cols;


		$q="select ".$c->group."
		    from ".$t_at." 
			group by ".$c->group;
			
		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
	
		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$out[] = $row[$c->group];
		}
		$res->free();
		return $out;

	}	
}
?>

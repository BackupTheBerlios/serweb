<?php
/*
 * $Id: method.get_domain.php,v 1.4 2005/11/04 13:23:02 kozlik Exp $
 */

class CData_Layer_get_domain {
	var $required_methods = array();
	
	/**
	 *  return array of associtive arrays containig domain names
	 *
	 *  Keys of associative arrays:
	 *    id
	 *    name
	 *
	 *  Possible options:
	 *
	 *	  order_by	(string)	default: 'name'
	 *		name of column for sorting. If false or empty, result is not sorted
	 *
	 *    filter	(array)     default: array()
	 *      associative array of pairs (column, value) which should be returned
	 *      
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return array			array of domain names or FALSE on error
	 */ 
	function get_domain($opt, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->domain;

	    $o_filter  = (isset($opt['filter'])) ? $opt['filter'] : array();
	    $o_order_by = (isset($opt['order_by'])) ? $opt['order_by'] : "name";

		$qw=" ".$this->get_sql_bool(true)." ";
		foreach($o_filter as $k=>$v){
			$qw .= "and ".$c->$k." = '".$v."' ";
		}

		$q="select ".$c->id.", ".$c->name."
		    from ".$config->data_sql->table_domain."
			where ".$qw; 
		if ($o_order_by) $q .= " order by ".$c->$o_order_by;

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]['id']       = $row[$c->id];
			$out[$i]['name']     = $row[$c->name];
			$out[$i]['primary_key']  = array('id' => &$out[$i]['id'], 
			                                 'name' => &$out[$i]['name']);
		}
		$res->free();

		return $out;			
	}
	
}
?>

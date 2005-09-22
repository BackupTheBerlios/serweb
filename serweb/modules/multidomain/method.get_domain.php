<?php
/*
 * $Id: method.get_domain.php,v 1.1 2005/09/22 14:29:16 kozlik Exp $
 */

class CData_Layer_get_domain {
	var $required_methods = array();
	
	/**
	 *  return array of associtive arrays containig domain names
	 *
	 *  Keys of associative arrays:
	 *    id
	 *    name
	 *    disabled
	 *
	 *  Possible options:
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

	    $o_filter = (isset($opt['filter'])) ? $opt['filter'] : array();

		$qw=" true ";
		foreach($o_filter as $k=>$v){
			$qw .= "and ".$c->$k." = '".$v."' ";
		}

		$q="select ".$c->id.", ".$c->name.", ".$c->disabled."
		    from ".$config->data_sql->table_domain."
			where ".$qw." 
			order by ".$c->name;

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]['id']       = $row[$c->id];
			$out[$i]['name']     = $row[$c->name];
			$out[$i]['disabled'] = $row[$c->disabled];
			$out[$i]['primary_key']  = array('id' => &$out[$i]['id'], 
			                                 'name' => &$out[$i]['name']);
		}
		$res->free();

		return $out;			
	}
	
}
?>

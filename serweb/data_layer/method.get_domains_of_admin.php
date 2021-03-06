<?php
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.get_domains_of_admin.php,v 1.5 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method get domains managed by specified admin
 * 
 *	@package    serweb
 */ 
class CData_Layer_get_domains_of_admin {
	var $required_methods = array();
	
	/**
	 *  return array of domain ids which can administer given user
	 *
	 *
	 *  Possible options:
	 *	 - none
	 *      
	 *	@param string $uid		
	 *	@param array $opt		associative array of options
	 *	@return array			array of domain ids or FALSE on error
	 */ 
	function get_domains_of_admin($uid, $opt){
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

		$an = &$config->attr_names;

		$q="select ".$c->did." 
		    from ".$t_name."
			where  ".$c->name."  = '".$an['admin']."' and 
			       ".$c->value." = ".$this->sql_format($uid, "s")." and 
			      (".$c->flags." & ".$f['DB_DELETED'].") = 0 and
				  (".$c->flags." & ".$f['DB_FOR_SERWEB'].") = ".$f['DB_FOR_SERWEB'];

		
		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
		
		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]   = $row[$c->did];
		}
		$res->free();

		return $out;			
	}
	
}

?>

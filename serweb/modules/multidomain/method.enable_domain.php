<?php
/*
 * $Id: method.enable_domain.php,v 1.1 2005/09/22 14:29:16 kozlik Exp $
 */

class CData_Layer_enable_domain {
	var $required_methods = array();
	
	/**
	 *  Enable or disable domain
	 *
	 *  Possible options:
	 *
	 *    id		(int)   	default: null
	 *      id of domain which will be en/disabled
	 *      either id or name is required
	 *      
	 *    name		(string)	default: null
	 *      name of domain which will be en/disabled
	 *      either id or name is required
	 *      
	 *    disable	(bool)  	default: false
	 *      if true domain will be disabled, otherwise wil be enabled
	 *      
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function enable_domain($opt, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->domain;

	    $o_id = (isset($opt['id'])) ? $opt['id'] : null;
	    $o_name = (isset($opt['name'])) ? $opt['name'] : null;
	    $o_disable = (isset($opt['disable'])) ? $opt['disable'] : false;

		if (is_null($o_id) and is_null($o_name)) {
			log_errors(PEAR::raiseError('domain for en/disable is not specified'), $errors); 
			return false;
		}

		$qw="";
		if ($o_id) $qw .= $c->id."=".$o_id;
		if ($qw and $o_name) $qw .= " and ";
		if ($o_name) $qw .= $c->name."='".$o_name."'";
		

		$q="update ".$config->data_sql->table_domain."
			set ".$c->disabled." = ".($o_disable ? 1 : 0)."
			where ".$qw;


		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			return false;
		}

		return true;
	}
	
}
?>

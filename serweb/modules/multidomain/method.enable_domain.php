<?php
/*
 * $Id: method.enable_domain.php,v 1.2 2005/10/05 11:22:52 kozlik Exp $
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
	 *      this option is REQUIRED
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

		$c = &$config->data_sql->dom_pref;

	    $o_id = (isset($opt['id'])) ? $opt['id'] : null;
	    $o_disable = (isset($opt['disable'])) ? $opt['disable'] : false;

		if (is_null($o_id)) {
			log_errors(PEAR::raiseError('domain for en/disable is not specified'), $errors); 
			return false;
		}

		if ($o_disable)
			$q = "insert into ".$config->data_sql->table_dom_preferences."
			             (".$c->id.", ".$c->att_name.", ".$c->att_value.")
			      values ('".$o_id."', 'disabled', '1')";
		else
			$q = "delete from ".$config->data_sql->table_dom_preferences."
			      where ".$c->id." = '".$o_id."' and
				        ".$c->att_name." = 'disabled'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			return false;
		}

		return true;
	}
	
}
?>

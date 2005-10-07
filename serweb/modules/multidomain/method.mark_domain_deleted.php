<?php
/*
 * $Id: method.mark_domain_deleted.php,v 1.1 2005/10/07 07:28:00 kozlik Exp $
 */

class CData_Layer_mark_domain_deleted {
	var $required_methods = array();
	
	/**
	 *  Mark domain as deleted
	 *
	 *  Possible options:
	 *
	 *    id		(int)   	default: null
	 *      id of domain which will be deleted
	 *      REQUIRED
	 *      
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function mark_domain_deleted($opt, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->dom_pref;

	    $o_id = (isset($opt['id'])) ? $opt['id'] : null;

		if (is_null($o_id)) {
			log_errors(PEAR::raiseError('domain for mark as deleted is not specified'), $errors); 
			return false;
		}

		$q = "insert into ".$config->data_sql->table_dom_preferences."
		             (".$c->id.", ".$c->att_name.", ".$c->att_value.")
		      values ('".$o_id."', 'deleted', '".time()."')";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			return false;
		}

		return true;
	}
	
}

?>

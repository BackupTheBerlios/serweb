<?php
/*
 * $Id: method.add_domain_alias.php,v 1.1 2005/09/22 14:29:16 kozlik Exp $
 */

class CData_Layer_add_domain_alias {
	var $required_methods = array();
	
	/**
	 *	add new domain alias
	 *
	 *  Keys of associative array $values:
	 *    id
	 *    name
	 *      
	 *  Possible options parameters:
	 *	 none
	 *
	 *	@param array $values	values
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */
	function add_domain_alias($values, $opt, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->domain;


		$q="insert into ".$config->data_sql->table_domain." (
				   ".$c->id.",
				   ".$c->name.", 
				   ".$c->disabled."
		    ) 
			values (
				   '".$values['id']."', 
				   '".$values['name']."', 
				   0
			 )";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			return false;
		}

		return true;
	}
	
}

?>

<?php
/*
 * $Id: method.add_domain_alias.php,v 1.2 2005/10/07 07:28:00 kozlik Exp $
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
				   ".$c->name."
		    ) 
			values (
				   '".$values['id']."', 
				   '".$values['name']."'
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

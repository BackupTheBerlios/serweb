<?php
/*
 * $Id: method.add_domain_alias.php,v 1.3 2005/12/22 12:38:54 kozlik Exp $
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
	 *	
	 *	  set_canon	(bool)	default: false
	 *		set domain alias canonical
	 *
	 *	@param array $values	values
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */
	function add_domain_alias($values, $opt, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		/* table's name */
		$td_name = &$config->data_sql->domain->table_name;
		/* col names */
		$cd = &$config->data_sql->domain->cols;
		/* flags */
		$fd = &$config->data_sql->domain->flag_values;

		$ca = &$config->attr_names;


		$opt['set_canon'] = empty($opt['set_canon']) ? false : true;
		$opt['disabled']  = empty($opt['disabled'])  ? false : true;


		$global_attrs = &Global_Attrs::singleton();
		if (false === $flags = $global_attrs->get_attribute($ca['domain_default_flags'])) return false;

		if (!is_numeric($flags)){
			ErrorHandler::log_errors(PEAR::raiseError("Attribute '".$ca['domain_default_flags']."' is not defined or is not a number"));
			return false;
		}

		if ($opt['set_canon']) $flags = $flags | $fd['DB_CANON'];
		if ($opt['disabled'])  $flags = $flags | $fd['DB_DISABLED'];

		$q="insert into ".$td_name." (
				   ".$cd->did.",
				   ".$cd->name.",
				   ".$cd->flags."
		    ) 
			values (
				   '".$values['id']."', 
				   '".$values['name']."',
				   ".$flags."
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

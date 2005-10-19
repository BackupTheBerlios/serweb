<?php
/*
 * $Id: method.update_domain_attribute.php,v 1.1 2005/10/19 10:32:14 kozlik Exp $
 */

class CData_Layer_update_domain_attribute {
	var $required_methods = array();

	/**
	 *  update customer table by $values
	 *
	 *  Keys of associative array $values:
	 *    name
	 *    address
	 *    email
	 *    phone
	 *
	 *  Possible options:
	 *
	 *    primary_key	(array) required
	 *      contain primary key of record which should be updated
	 *      The array contain the same keys as functon get_customers returned in entry 'primary_key'
	 *
	 *    insert  	(bool) default:true
	 *      if true, function insert new record, otherwise update old record
	 *
	 *	@param array $values	values
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function update_domain_attribute($domain_id, $att_name, $att_value, $opt, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->dom_pref;

		$opt_insert = isset($opt['insert']) ? (bool)$opt['insert'] : false;

		if ($opt_insert) {

			$q="insert into ".$config->data_sql->table_dom_preferences." (
					   ".$c->id.", ".$c->att_name.", ".$c->att_value."
			    ) 
				values (
					   '".$domain_id."', '".$att_name."', '".$att_value."'
				 )";
		}
		else {
			$q="update ".$config->data_sql->table_dom_preferences." 
			    set ".$c->att_value."='".$att_value."'  
				where ".$c->id."='".$domain_id."' and ".$c->att_name."='".$att_name."'";
		}


		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			return false;
		}
		return true;

	}

}

?>

<?php
/*
 * $Id: method.update_customer.php,v 1.2 2005/10/05 12:38:26 kozlik Exp $
 */

class CData_Layer_update_customer {
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
	function update_customer($values, $opt, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$c = &$config->data_sql->customer;

		$opt_insert = isset($opt['insert']) ? (bool)$opt['insert'] : false;
		if (!$opt_insert and 
				(!isset($opt['primary_key']) or 
				 !is_array($opt['primary_key']) or 
				 empty($opt['primary_key']))){
			log_errors(PEAR::raiseError('primary key is missing'), $errors); return false;
		}


		if ($opt_insert) {

			$q="insert into ".$config->data_sql->table_customer." (
					   ".$c->name.", ".$c->address.", ".$c->email.", ".$c->phone."
			    ) 
				values (
					   '".$values['name']."', '".$values['address']."', '".$values['email']."', '".$values['phone']."'
				 )";
		}
		else {
			$q="update ".$config->data_sql->table_customer." 
			    set ".$c->name."='".$values['name']."',  
			        ".$c->address."='".$values['address']."', 
			        ".$c->email."='".$values['email']."', 
			        ".$c->phone."='".$values['phone']."'
				where ".$c->id."='".$opt['primary_key']['id']."'";
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

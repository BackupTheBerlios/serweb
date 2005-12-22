<?php
/*
 * $Id: method.update_customer.php,v 1.3 2005/12/22 12:38:54 kozlik Exp $
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

		/* table's name */
		$tc_name = &$config->data_sql->customers->table_name;
		/* col names */
		$cc = &$config->data_sql->customers->cols;

		$opt_insert = isset($opt['insert']) ? (bool)$opt['insert'] : false;
		if (!$opt_insert and 
				(!isset($opt['primary_key']) or 
				 !is_array($opt['primary_key']) or 
				 empty($opt['primary_key']))){
			log_errors(PEAR::raiseError('primary key is missing'), $errors); return false;
		}


		if ($opt_insert) {

			$q="insert into ".$tc_name." (
					   ".$cc->name.", ".$cc->address.", ".$cc->email.", ".$cc->phone."
			    ) 
				values (
					   '".$values['name']."', '".$values['address']."', '".$values['email']."', '".$values['phone']."'
				 )";
		}
		else {
			$q="update ".$tc_name." 
			    set ".$cc->name."='".$values['name']."',  
			        ".$cc->address."='".$values['address']."', 
			        ".$cc->email."='".$values['email']."', 
			        ".$cc->phone."='".$values['phone']."'
				where ".$cc->cid."='".$opt['primary_key']['cid']."'";
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

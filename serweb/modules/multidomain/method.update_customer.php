<?php
/*
 * $Id: method.update_customer.php,v 1.4 2006/01/13 09:25:58 kozlik Exp $
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
	 *	  new_id	(bool)
	 *		In this option is returned ID of new created customer. 
	 *		Option is created only if 'insert'=true
	 *
	 *	@param array $values	values
	 *	@param array $opt		associative array of options
	 *	@param array $errors	error messages
	 *	@return bool			TRUE on success, FALSE on failure
	 */ 
	function update_customer($values, &$opt, &$errors){
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
			$q = "select max(".$cc->cid.") from ".$tc_name;

			$res=$this->db->query($q);
			if (DB::isError($res)) {ErrorHandler::log_errors($res, $errors); return false;}

			$next_id = 0;
			if ($row=$res->fetchRow(DB_FETCHMODE_ORDERED)){
				if (!is_null($row[0])) $next_id = $row[0] + 1;
			}

			$q="insert into ".$tc_name." (
					   ".$cc->cid.", ".$cc->name.", ".$cc->address.", ".$cc->email.", ".$cc->phone."
			    ) 
				values (
					   ".$next_id.", '".$values['name']."', '".$values['address']."', '".$values['email']."', '".$values['phone']."'
				 )";
				 
			$opt['new_id'] = $next_id;
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

<?
/*
 * $Id: method.update_speed_dial.php,v 1.1 2004/09/06 11:03:13 kozlik Exp $
 */

/*
 *  Function update speed dial entry of $user by $values
 *
 *  Keys of associative array $values:
 *    new_request_uri
 *    first_name
 *    last_name
 *    primary_key            - array - reflect primary key without user identification
 *
 *
 *  Possible options parameters:
 *
 *    primary_key	(array) required
 *      contain primary key (without user specification) of record which should be updated
 *      The array contain the same keys as functon get_speed_dials returned in entry 'primary_key'
 *
 *    insert  	(bool) default:true
 *      if true, function insert new record, otherwise update old record
 *
 */ 

class CData_Layer_update_speed_dial {
	var $required_methods = array();

	function update_speed_dial($user, $values, $opt, &$errors){
		global $config, $lang_str;
		
		if (!$this->connect_to_db($errors)) return false;

		$opt_insert = isset($opt['insert']) ? (bool)$opt['insert'] : false;
		if (!isset($opt['primary_key']) or !is_array($opt['primary_key']) or empty($opt['primary_key'])){
			log_errors(PEAR::raiseError('primary key is missing'), $errors); return false;
		}


		if ($opt_insert) {
			$att=$this->get_indexing_sql_insert_attribs($user);

			$q="insert into ".$config->data_sql->table_speed_dial." (
			           ".$att['attributes'].", 
					   username_from_req_uri,
					   domain_from_req_uri, 
					   new_request_uri, 
					   first_name, 
					   last_name
			    ) 
				values (
				       ".$att['values'].", 
					   '".$opt['primary_key']['username_from_req_uri']."',
					   '".$opt['primary_key']['domain_from_req_uri']."',
					   '".$values['new_request_uri']."', 
					   '".$values['first_name']."', 
					   '".$values['last_name']."'
				 )";
		}
		else {
			$q="update ".$config->data_sql->table_speed_dial." 
			    set new_request_uri='".$values['new_request_uri']."', 
					first_name='".$values['first_name']."', 
					last_name='".$values['last_name']."' 
				where username_from_req_uri='".$opt['primary_key']['username_from_req_uri']."' and 
				      domain_from_req_uri='".$opt['primary_key']['domain_from_req_uri']."' and 
					  ".$this->get_indexing_sql_where_phrase($user);

		}

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_ALREADY_EXISTS)
				$errors[]=$lang_str['err_speed_dial_already_exists'];
			else log_errors($res, $errors); 
			return false;
		}
		return true;

	}

}
?>

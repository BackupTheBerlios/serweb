<?
/*
 * $Id: method.update_sd_request.php,v 1.2 2004/08/09 23:04:57 kozlik Exp $
 */

class CData_Layer_update_sd_request {
	var $required_methods = array();
	
	function update_SD_request($user, $sd, $new_uri, $usrnm_from_uri, $domain_from_uri, &$errors){
		global $config, $lang_str;
		
		if (!$this->connect_to_db($errors)) return false;

		if ($sd) $q="update ".$config->data_sql->table_speed_dial." set new_request_uri='$new_uri', username_from_req_uri='$usrnm_from_uri', domain_from_req_uri='$domain_from_uri' ".
			"where username_from_req_uri='$sd' and ".$this->get_indexing_sql_where_phrase($user);
		else {
			$att=$this->get_indexing_sql_insert_attribs($user);

			$q="insert into ".$config->data_sql->table_speed_dial." (".$att['attributes'].", username_from_req_uri, domain_from_req_uri, new_request_uri) ".
			"values (".$att['values'].", '$usrnm_from_uri', '$domain_from_uri', '$new_uri')";
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

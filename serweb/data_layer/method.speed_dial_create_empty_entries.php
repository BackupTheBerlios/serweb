<?
/*
 * $Id: method.speed_dial_create_empty_entries.php,v 1.1 2004/11/29 21:30:02 kozlik Exp $
 */

/*
 *  Function create empty entries in table speed dial with username in given interval
 *
 *
 *  Possible options parameters:
 *
 *    in_domain  (string)
 *      if is set use this domain in select where phrase
 *
 *    set_domain (string)
 *      use this domain in insert 
 *  
 *    length  (integer)
 *      length of inserted usernames with leading zeros, default is derived from lenth of $to parameter
 *
 */ 

class CData_Layer_speed_dial_create_empty_entries {
	var $required_methods = array();

	function speed_dial_create_empty_entries($user, $from, $to, $opt, &$errors){
		global $config, $lang_str;
		
		if (!$this->connect_to_db($errors)) return false;

    	$opt_in_domain = (isset($opt['in_domain'])) ? $opt['in_domain'] : '';
    	$opt_set_domain = (isset($opt['set_domain'])) ? $opt['set_domain'] : '';
    	$opt_length = (isset($opt['length'])) ? $opt['length'] : ceil(log10($to));
		
		$where_phrase = " and username_from_req_uri rlike '^[0-9]+$' ";	// get only numerical usernames in given range
		$where_phrase .= " and abs(username_from_req_uri) >= ".$from." ";  //abs() converts string to integer
		$where_phrase .= " and abs(username_from_req_uri) <= ".$to." ";
		if ($opt_in_domain) $where_phrase .= " and domain_from_req_uri = '".$opt_in_domain."' ";
		
		$q="select username_from_req_uri from ".$config->data_sql->table_speed_dial.
			" where ".$this->get_indexing_sql_where_phrase($user).$where_phrase." order by username_from_req_uri";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
			
		$i = $from;			
		
		while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC)){
			if ($i == $row['username_from_req_uri']) {$i++;}
			elseif ($i < $row['username_from_req_uri']){
				if (false === $this->speed_dial_cee_fill($user, $i, $row['username_from_req_uri']-1, $opt_length, $opt_set_domain, $errors)) return false;
				$i = $row['username_from_req_uri']+1;
			} 
			// else nothing
		}

		if ($i <= $to){
			if (false === $this->speed_dial_cee_fill($user, $i, $to, $opt_length, $opt_set_domain, $errors)) return false;
			$i = $row['username_from_req_uri']+1;
		}
		
		return true;
	}

	
	function speed_dial_cee_fill($user, $from, $to, $length, $set_domain, &$errors){
		global $config;
		$att=$this->get_indexing_sql_insert_attribs($user);

		for ($i=$from; $i<=$to; $i++){

			$q="insert into ".$config->data_sql->table_speed_dial." (
			           ".$att['attributes'].", 
					   username_from_req_uri".
					   ($set_domain ? ",domain_from_req_uri" : "").
			    ") 
				values (
				       ".$att['values'].", 
					   '".sprintf("%0".$length."u", $i)."'".
					   ($set_domain ? (",'".$set_domain."'") : "").
				")";
		
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		}
		return true;
	}
}
?>

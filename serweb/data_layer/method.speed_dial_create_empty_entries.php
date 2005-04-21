<?
/*
 * $Id: method.speed_dial_create_empty_entries.php,v 1.2 2005/04/21 15:09:46 kozlik Exp $
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

		$c = &$config->data_sql->speed_dial;
		
		$where_phrase = " and ".$c->sd_username." rlike '^[0-9]+$' ";	// get only numerical usernames in given range
		$where_phrase .= " and abs(".$c->sd_username.") >= ".$from." ";  //abs() converts string to integer
		$where_phrase .= " and abs(".$c->sd_username.") <= ".$to." ";
		if ($opt_in_domain) $where_phrase .= " and ".$c->sd_domain." = '".$opt_in_domain."' ";
		
		$q="select ".$c->sd_username." from ".$config->data_sql->table_speed_dial.
			" where ".$this->get_indexing_sql_where_phrase($user).$where_phrase." order by ".$c->sd_username;

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
			
		$i = $from;			
		
		while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC)){
			if ($i == $row[$c->sd_username]) {$i++;}
			elseif ($i < $row[$c->sd_username]){
				if (false === $this->speed_dial_cee_fill($user, $i, $row[$c->sd_username]-1, $opt_length, $opt_set_domain, $errors)) return false;
				$i = $row[$c->sd_username]+1;
			} 
			// else nothing
		}

		if ($i <= $to){
			if (false === $this->speed_dial_cee_fill($user, $i, $to, $opt_length, $opt_set_domain, $errors)) return false;
			$i = $row[$c->sd_username]+1;
		}
		
		return true;
	}

	
	function speed_dial_cee_fill($user, $from, $to, $length, $set_domain, &$errors){
		global $config;
		$att=$this->get_indexing_sql_insert_attribs($user);

		$c = &$config->data_sql->speed_dial;

		for ($i=$from; $i<=$to; $i++){

			$q="insert into ".$config->data_sql->table_speed_dial." (
			           ".$att['attributes'].", 
					   ".$c->sd_username.
					   ($set_domain ? ",".$c->sd_domain : "").
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

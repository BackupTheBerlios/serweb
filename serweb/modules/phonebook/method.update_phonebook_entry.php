<?
/*
 * $Id: method.update_phonebook_entry.php,v 1.2 2006/03/08 15:46:27 kozlik Exp $
 */

class CData_Layer_update_phonebook_entry {
	var $required_methods = array();
	
	function update_phonebook_entry($user, $pbid, $fname, $lname, $sip_uri, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		if ($pbid) 
			$q="update ".$config->data_sql->table_phonebook." 
			    set fname   = ".$this->sql_format($fname,   "s").", 
				    lname   = ".$this->sql_format($lname,   "s").", 
					sip_uri = ".$this->sql_format($sip_uri, "s")." 
				where id=".$this->sql_format($pbid, "n")." and 
			       ".$this->get_indexing_sql_where_phrase($user);
		else {
			$att=$this->get_indexing_sql_insert_attribs($user);

			$q="insert into ".$config->data_sql->table_phonebook." 
					(fname, lname, sip_uri, ".$att['attributes'].") 
				values (".$this->sql_format($fname,   "s").", 
				        ".$this->sql_format($lname,   "s").", 
						".$this->sql_format($sip_uri, "s").", 
						".$att['values'].")";
		}
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;			
	}
	
}
?>

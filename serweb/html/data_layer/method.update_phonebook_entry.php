<?
/*
 * $Id: method.update_phonebook_entry.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_update_phonebook_entry {
	var $required_methods = array();
	
	function update_phonebook_entry($user, $pbid, $fname, $lname, $sip_uri, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		if ($pbid) $q="update ".$config->data_sql->table_phonebook." set fname='".$fname."', lname='".$lname."', sip_uri='".$sip_uri."' ".
			"where id=".$pbid." and ".$this->get_indexing_sql_where_phrase($user);
		else {
			$att=$this->get_indexing_sql_insert_attribs($user);

			$q="insert into ".$config->data_sql->table_phonebook." (fname, lname, sip_uri, ".$att['attributes'].") ".
			"values ('".$fname."', '".$lname."', '".$sip_uri."', ".$att['values'].")";
		}
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;			
	}
	
}
?>

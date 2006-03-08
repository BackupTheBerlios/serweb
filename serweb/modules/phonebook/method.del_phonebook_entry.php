<?
/*
 * $Id: method.del_phonebook_entry.php,v 1.2 2006/03/08 15:46:27 kozlik Exp $
 */

class CData_Layer_del_phonebook_entry {
	var $required_methods = array();
	
	function del_phonebook_entry($user, $pbid, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$q="delete from ".$config->data_sql->table_phonebook." 
		    where ".$this->get_indexing_sql_where_phrase($user)." and 
			      id=".$this->sql_format($pbid, "n");
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;			
	}
	
}
?>

<?
/*
 * $Id: method.del_phonebook_entry.php,v 1.1 2004/08/09 11:40:58 kozlik Exp $
 */

class CData_Layer_del_phonebook_entry {
	var $required_methods = array();
	
	function del_phonebook_entry($user, $pbid, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$q="delete from ".$config->data_sql->table_phonebook." where ".
			$this->get_indexing_sql_where_phrase($user)." and id=".$pbid;
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;			
	}
	
}
?>

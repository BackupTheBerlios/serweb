<?
/*
 * $Id: method.unsubscribe_event.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_unsubscribe_event {
	var $required_methods = array();
	
	function unsubscribe_event($user, $id, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="delete from ".$config->data_sql->table_event.
			" where ".$this->get_indexing_sql_where_phrase($user)." and id=".$id;
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		return true;
	}
	
}
?>

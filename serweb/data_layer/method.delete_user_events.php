<?
/*
 * $Id: method.delete_user_events.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_delete_user_events {
	var $required_methods = array();
	
	/*
	 * delete all records of user from events table
	 */
	
	function delete_user_events($user, &$errors){
	 	global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="delete from ".$config->data_sql->table_event." where ".$this->get_indexing_sql_where_phrase($user);

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_NOSUCHTABLE) return true;  //expected, table mayn't exist in installed version
			else {log_errors($res, $errors); return false;}
		}
		return true;
	}
}
?>
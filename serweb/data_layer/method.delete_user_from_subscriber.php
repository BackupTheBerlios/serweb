<?
/*
 * $Id: method.delete_user_from_subscriber.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_delete_user_from_subscriber {
	var $required_methods = array();
	
	/*
	 * delete user record from subscriber
	 */

	function delete_user_from_subscriber($user, &$errors){
	 	global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="delete from ".$config->data_sql->table_subscriber." where ".$this->get_indexing_sql_where_phrase($user);
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		return true;
	}
}
?>

<?
/*
 * $Id: method.del_user_from_pending.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_del_user_from_pending {
	var $required_methods = array();
	
	function del_user_from_pending($confirmation, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$q="delete from ".$config->data_sql->table_pending." where confirmation='".$confirmation."'";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		return true;
	}
	
}
?>

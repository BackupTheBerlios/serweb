<?
/*
 * $Id: method.del_user_from_subscriber.php,v 1.1 2004/08/09 11:40:58 kozlik Exp $
 */

class CData_Layer_del_user_from_subscriber {
	var $required_methods = array();
	
	function del_user_from_subscriber($confirmation, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$q="delete from ".$config->data_sql->table_subscriber." where confirmation='".$confirmation."'";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		return true;
	}
	
}
?>

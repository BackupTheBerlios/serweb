<?
/*
 * $Id: method.del_privilege_of_user.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_del_privilege_of_user {
	var $required_methods = array();
	
	/* 
	 * dele privilege of user, if priv_value is NULL then all values of multivalue privilege are deleted 
	 */
	 
	function del_privilege_of_user($user, $priv_name, $priv_value, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$q="delete from ".$config->data_sql->table_admin_privileges." where ".
			$this->get_indexing_sql_where_phrase($user)." and priv_name='".$priv_name."'".(is_null($priv_value)?"":"and priv_value='".$priv_value."'");

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		return true;
		
	}
	
}
?>

<?
/*
 * $Id: method.del_attribute.php,v 1.1 2004/08/09 11:40:58 kozlik Exp $
 */

class CData_Layer_del_attribute {
	var $required_methods = array();
	
	/* 
	 * delete attribute named $att_name from usr_preferences_types and from usr_preferences
	 */
	 
	function del_attribute($att_name, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		//delete attribute from user_preferences table
		$q="delete from ".$config->data_sql->table_user_preferences.
			" where attribute='".$att_name."'";
		$res=$this->db->query($q); 
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		//delete attribute form user_preferences_types table
		$q="delete from ".$config->data_sql->table_user_preferences_types.
			" where att_name='".$att_name."'";
		$res=$this->db->query($q); 
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;
	}
	
}
?>

<?
/*
 * $Id: method.update_attribute.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_update_attribute {
	var $required_methods = array();
	
	/* 
	 * update attribute named $att_edit in usr_preferences_types
	 * if name of atribute is changed, update also usr_preferences
	 */
	 
	function update_attribute($att_edit, $att_name, $att_rich_type, $att_raw_type, $default_value, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		if ($att_edit) 
			$q="update ".$config->data_sql->table_user_preferences_types." ".
				"set att_name='$att_name', att_rich_type='$att_rich_type', default_value='$default_value', ".
					"att_raw_type='".$att_raw_type."'".
				"where att_name='$att_edit'";
		else 
			$q="insert into ".$config->data_sql->table_user_preferences_types." (att_name, att_rich_type, default_value, att_raw_type) ".
				"values ('$att_name', '$att_rich_type', '$default_value', '".$att_raw_type."')";

		$res=$this->db->query($q); 
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_ALREADY_EXISTS)
				$errors[]="This attribute name already exists - choose another";
			else log_errors($res, $errors); 
			return false;
		}

		//if name of attribute is changed, update user_preferences table
		if ($att_edit and $att_edit!=$att_name){
			$q="update ".$config->data_sql->table_user_preferences." ".
				"set attribute='$att_name' where attribute='$att_edit'";

			$res=$this->db->query($q); 
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		}

		return true;
	}
	
}
?>

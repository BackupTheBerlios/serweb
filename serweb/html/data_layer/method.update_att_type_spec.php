<?
/*
 * $Id: method.update_att_type_spec.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_update_att_type_spec {
	var $required_methods = array();
	
	/* 
	 * update att_type_spec of attribute named $att_name in usr_preferences_types
	 */
	 
	function update_att_type_spec($att_name, $att_type_spec, $default_value, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="update ".$config->data_sql->table_user_preferences_types.
			" set att_type_spec='".$att_type_spec."' ".
				(is_null($default_value)?
					"":
					", default_value='".$default_value."'").
			" where att_name='".$att_name."'";
		$res=$this->db->query($q); 
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		return true;
	}
	
}
?>

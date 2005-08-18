<?
/*
 * $Id: method.get_attribute.php,v 1.1 2005/08/18 12:08:49 kozlik Exp $
 */

class CData_Layer_get_attribute {
	var $required_methods = array();
	
	/* 
	 * return details of attribute named $att_name
	 */
	 
	function get_attribute($att_name, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="select att_name, att_rich_type, att_type_spec, default_value from ".$config->data_sql->table_user_preferences_types.
			" where att_name='".$att_name."'";
		$res=$this->db->query($q); 
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();
		return $row;
	}
	
}
?>

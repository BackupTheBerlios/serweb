<?
/*
 * $Id: method.update_attribute_of_user.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_update_attribute_of_user {
	var $required_methods = array();
	
	/* 
	 * set $attribute of $value to $user
	 */
	 
	function update_attribute_of_user($user, $attribute, $value, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$att=$this->get_indexing_sql_insert_attribs($user);
		
		$q="replace into ".$config->data_sql->table_user_preferences." (".$att['attributes'].", attribute, value) ".
			"values (".$att['values'].", '".$attribute."', '".$value."')";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		return true;

	}
	
}
?>

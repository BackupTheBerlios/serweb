<?
/*
 * $Id: method.get_att_values.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_get_att_values {
	var $required_methods = array();
	
	/* 
	 * return asociative array of all preferences of $user in $attributes parameter
	 */
	 
	function get_att_values($user, &$attributes, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="select attribute, value from ".$config->data_sql->table_user_preferences.
			" where ".$this->get_indexing_sql_where_phrase($user);
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
	
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
			$attributes[$row->attribute]->att_value = $row->value;
		}
		$res->free();
		return true;

	}
	
}
?>

<?
/*
 * $Id: method.get_attribute_of_user.php,v 1.1 2004/09/16 17:19:46 kozlik Exp $
 */

/*
 *  Function return value of attribute named $attribute of user $user
 *  on error or if attribute not found return false
 *
 *
 *  Possible options parameters:
 *    for the present none
 *
 */ 

class CData_Layer_get_attribute_of_user {
	var $required_methods = array();
	
	/* 
	 * gat $attribute value of $user
	 */
	 
	function get_attribute_of_user($user, $attribute, $opt, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="select value from ".$config->data_sql->table_user_preferences."
			where ".$this->get_indexing_sql_where_phrase($user)." and attribute='".$attribute."'";
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		if ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
			return $row->value;
		}
		
		/* attribute not found try get default value */
		
		$q="select default_value from ".$config->data_sql->table_user_preferences_types."
			where att_name='".$attribute."'";
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		if ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
			return $row->default_value;
		}
		
		log_errors(PEAR::RaiseError("Attribute named '".$attribute."' does not exists"), $errors);
		return false; /* attribute not found */
	}
	
}
?>

<?
/*
 * $Id: method.set_unset_avp_of_user_to_default.php,v 1.1 2004/12/02 12:57:23 kozlik Exp $
 */

class CData_Layer_set_unset_avp_of_user_to_default {
	var $required_methods = array();
	
	/* 
	 * set $attribute of $value to $user
	 */
	 
	function set_unset_avp_of_user_to_default($user, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		/* get names of all atributes */
		$q = "select att_name from ".$config->data_sql->table_user_preferences_types;
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$all_attributes = array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$all_attributes[] = $row['att_name']; 
		}
		
		/* get names of atributes which are set for user */
		$q = "select attribute from ".$config->data_sql->table_user_preferences.
			 " where ".$this->get_indexing_sql_where_phrase($user);
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$user_attributes = array();
		while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)){
			$user_attributes[] = $row['attribute']; 
		}

		/* get unset attributes */
		$unset_attributes = array_diff($all_attributes, $user_attributes);

		/* set unset attributes */

		$att=$this->get_indexing_sql_insert_attribs($user);
		
		foreach ($unset_attributes as $row){
			$q="insert into ".$config->data_sql->table_user_preferences." (".$att['attributes'].", attribute, value) 
				select ".$att['values'].", '".$row."', default_value 
				from ".$config->data_sql->table_user_preferences_types." 
				where att_name='".$row."'";
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		}

		return true;

	}
	
}
?>

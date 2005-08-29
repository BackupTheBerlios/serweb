<?
/*
 * $Id: method.update_attribute_of_user.php,v 1.2 2005/08/29 13:28:10 kozlik Exp $
 */

class CData_Layer_update_attribute_of_user {
	var $required_methods = array();
	
	/* 
	 * set $attribute of $value to $user
	 */
	 
	function update_attribute_of_user($user, $attribute, $value, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;
		
		if ($this->db_host['parsed']['phptype'] == 'mysql'){
			$att=$this->get_indexing_sql_insert_attribs($user);

			$q="replace into ".$config->data_sql->table_user_preferences." (".$att['attributes'].", attribute, value) ".
				"values (".$att['values'].", '".$attribute."', '".$value."')";
	
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			return true;
		}
		else{
			$q="update ".$config->data_sql->table_user_preferences." 
			    set value = '".$value."' 
				where attribute = '".$attribute."' and ".$this->get_indexing_sql_where_phrase($user);
	
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}

			$affected = $this->db->affectedRows();
			if (DB::isError($affected)) {log_errors($res, $errors); return false;}

			// test if update affected some row
			if ($affected) return true;

			$att=$this->get_indexing_sql_insert_attribs($user);
			
			// NO! we must insert row for this attribute
			$q="insert into ".$config->data_sql->table_user_preferences." (".$att['attributes'].", attribute, value) ".
				"values (".$att['values'].", '".$attribute."', '".$value."')";
	
			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
			return true;
			
		}

	}
	
}
?>

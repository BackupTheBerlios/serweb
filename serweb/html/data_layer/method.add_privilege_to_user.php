<?
/*
 * $Id: method.add_privilege_to_user.php,v 1.1 2004/08/09 11:40:58 kozlik Exp $
 */

class CData_Layer_add_privilege_to_user {
	var $required_methods = array();
	
	/* 
	 * add privilege to user, parameter $update mean that record in DB should be updated, not inserted 
	 */
	 
	function add_privilege_to_user($user, $priv_name, $priv_value, $update, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		if ($update){ /* if privilege is in db we must update its value */
			$q="update ".$config->data_sql->table_admin_privileges." set priv_value='".$priv_value."' ".
				"where ".$this->get_indexing_sql_where_phrase($user)." and priv_name='".$priv_name."'";
		} else { /* otherwise we insert privilege with right value */
			$att=$this->get_indexing_sql_insert_attribs($user);
		
			$q="insert into ".$config->data_sql->table_admin_privileges." (".$att['attributes'].", priv_name, priv_value) ".
				"values (".$att['values'].", '".$priv_name."', '".$priv_value."')";
		}

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		return true;

	}
	
}
?>

<?
/*
 * $Id: method.get_admin_acl_privileges.php,v 1.1 2005/08/24 11:57:51 kozlik Exp $
 */

class CData_Layer_get_admin_ACL_privileges {
	var $required_methods = array();
	
	function get_admin_ACL_privileges($user, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="select priv_value from ".$config->data_sql->table_admin_privileges.
			" where ".$this->get_indexing_sql_where_phrase($user).
					" and priv_name='acl_control'";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
	
		$ACL_control=array();
		while ($row = $res->fetchRow(DB_FETCHMODE_OBJECT)) $ACL_control[]=$row->priv_value;
		$res->free();
		return $ACL_control;
	}
	
}
?>

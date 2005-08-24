<?
/*
 * $Id: method.get_acl_of_user.php,v 1.1 2005/08/24 11:57:51 kozlik Exp $
 */

class CData_Layer_get_acl_of_user {
	var $required_methods = array();

	/*
	 * get ACL of user
	 */
	
	function get_acl_of_user($user, &$errors){
		global $config;
	
		if (!$this->connect_to_db($errors)) return false;

		$q="select grp from ".$config->data_sql->table_grp.
			" where ".$this->get_indexing_sql_where_phrase($user).
			" order by grp";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
	
		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)){
			$out[]['grp']=$row->grp;
		} //while
		$res->free();			

		return $out;
	}
}
?>

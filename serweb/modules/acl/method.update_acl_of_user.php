<?
/*
 * $Id: method.update_acl_of_user.php,v 1.2 2006/03/08 15:46:26 kozlik Exp $
 */

class CData_Layer_update_ACL_of_user {
	var $required_methods = array();
	
	function update_ACL_of_user($user, $grp, $act, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		if ($act=='set'){
			$att=$this->get_indexing_sql_insert_attribs($user);
			
			$q="insert into ".$config->data_sql->table_grp." (".$att['attributes'].", grp, last_modified) ".
				"values (".$att['values'].", 
				         ".$this->sql_format($grp, "s").", 
						 now())";
		}
		else
			$q="delete from ".$config->data_sql->table_grp." 
			    where ".$this->get_indexing_sql_where_phrase($user)." and 
				       grp = ".$this->sql_format($grp, "s");

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		return true;
	}
	
}
?>

<?
/*
 * $Id: method.delete_user_aliases.php,v 1.3 2005/04/21 15:09:45 kozlik Exp $
 */

class CData_Layer_delete_user_aliases {
	var $required_methods = array();
	
	/*
	 * delete all user's aliases
	 */

	function delete_user_aliases($user, &$errors){
	 	global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		if ($config->users_indexed_by=='uuid')	$q="delete from ".$config->data_sql->table_uuidaliases." where uuid='".$user->uuid."'";
		else $q="delete from ".$config->data_sql->table_aliases." where contact='sip:".addslashes($user->uname."@".$user->domain)."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		if ($config->use_table_uri) {
			$q="delete from ".$config->data_sql->table_uri."
				 where ".$this->get_indexing_sql_where_phrase($user);

			$res=$this->db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		}
		
		return true;
	}
}
?>

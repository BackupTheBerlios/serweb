<?
/*
 * $Id: method.delete_user_aliases.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
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
		return true;
	}
}
?>

<?
/*
 * $Id: method.delete_user_vsilo.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_delete_user_vsilo {
	var $required_methods = array();
	
	/*
	 * delete all messages of user from message silo
	 */
	//!!!!!!!!!!!!!!!!!! doplnit mazani souboru
	function delete_user_vsilo($user, &$errors){
	 	global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		if ($config->users_indexed_by=='uuid') $q="delete from ".$config->data_sql->table_voice_silo." where r_uri like 'sip:".$user->uname."@".$user->domain."%'";
		else $q="delete from ".$config->data_sql->table_voice_silo." where uuid='".$user->uuid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_NOSUCHTABLE) return true;  //expected, table mayn't exist in installed version
			else {log_errors($res, $errors); return false;}
		}
		return true;
	}
}
?>

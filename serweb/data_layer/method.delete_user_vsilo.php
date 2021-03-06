<?
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.delete_user_vsilo.php,v 1.2 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for delete all voice messages for user
 * 
 *	@package    serweb
 */ 
class CData_Layer_delete_user_vsilo {
	var $required_methods = array();
	
	/**
	 * delete all messages of user from message silo
	 */
	//!!!!!!!!!!!!!!!!!! doplnit mazani souboru
	function delete_user_vsilo($user, &$errors){
	 	global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		if ($config->users_indexed_by=='uuid') $q="delete from ".$config->data_sql->table_voice_silo." where uuid='".$user->uuid."'";
		else $q="delete from ".$config->data_sql->table_voice_silo." where r_uri like 'sip:".$user->uname."@".$user->domain."%'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_NOSUCHTABLE) return true;  //expected, table mayn't exist in installed version
			else {log_errors($res, $errors); return false;}
		}
		return true;
	}
}
?>

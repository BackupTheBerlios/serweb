<?
/*
 * $Id: method.delete_user_msilo.php,v 1.2 2005/12/22 13:19:54 kozlik Exp $
 */

class CData_Layer_delete_user_msilo {
	var $required_methods = array();
	
	/*
	 * delete all messages of user from message silo
	 */
	
	function delete_user_msilo($uid){
	 	global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		$q="delete from ".$config->data_sql->table_message_silo." where uid='".$uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_NOSUCHTABLE) return true;  //expected, table mayn't exist in installed version
			else {ErrorHandler::log_errors($res); return false;}
		}
		return true;
	}
}
?>

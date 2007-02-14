<?
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.delete_user_phonebook.php,v 1.3 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for delete all records from phonebook of user
 * 
 *	@package    serweb
 */ 
class CData_Layer_delete_user_phonebook {
	var $required_methods = array();
	
	/**
	 *	delete all user's records from phonebook
	 */

	function delete_user_phonebook($uid){
	 	global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		$q="delete from ".$config->data_sql->table_phonebook." 
		    where uid='".$uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_NOSUCHTABLE) return true;  //expected, table mayn't exist in installed version
			else {ErrorHandler::log_errors($res); return false;}
		}
		return true;
	}
}
?>

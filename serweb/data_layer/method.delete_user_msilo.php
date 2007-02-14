<?
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.delete_user_msilo.php,v 1.4 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for delete all messages for user
 * 
 *	@package    serweb
 */ 
class CData_Layer_delete_user_msilo {
	var $required_methods = array();
	
	/**
	 * delete all messages of user from message silo
	 */
	
	function delete_user_msilo($uid){
	 	global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		$t_name = &$config->data_sql->msg_silo->table_name;	/* table's name */
		$c = &$config->data_sql->msg_silo->cols;				/* col names */

		$q="delete from ".$t_name." where ".$c->uid." = '".$uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_NOSUCHTABLE) return true;  //expected, table mayn't exist in installed version
			else {ErrorHandler::log_errors($res); return false;}
		}
		return true;
	}
}
?>

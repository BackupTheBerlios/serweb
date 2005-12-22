<?
/*
 * $Id: method.delete_user_credentials.php,v 1.1 2005/12/22 13:44:42 kozlik Exp $
 */

class CData_Layer_delete_user_credentials {
	var $required_methods = array();
	
	/*
	 * delete user record from subscriber
	 */

	function delete_user_credentials($uid){
	 	global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		/* table name */
		$t_name = &$config->data_sql->credentials->table_name;
		/* col names */
		$c = &$config->data_sql->credentials->cols;
		/* flags */
		$f = &$config->data_sql->credentials->flag_values;

		$q="delete from ".$t_name." 
		    where ".$c->uid." = '".$uid."'";
		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
		return true;
	}
}
?>

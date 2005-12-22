<?
/*
 * $Id: method.delete_user_uri.php,v 1.1 2005/12/22 13:44:42 kozlik Exp $
 */

class CData_Layer_delete_user_uri {
	var $required_methods = array();
	
	/*
	 * delete all user's aliases
	 */

	function delete_user_uri($uid){
	 	global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		/* table's name */
		$tu_name = &$config->data_sql->uri->table_name;
		/* col names */
		$cu = &$config->data_sql->uri->cols;
		/* flags */
		$fu = &$config->data_sql->uri->flag_values;


		$q="delete from ".$tu_name." 
		    where ".$cu->uid."='".$uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {ErrorHandler::log_errors($res); return false;}
		
		return true;
	}
}
?>

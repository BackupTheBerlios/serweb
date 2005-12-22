<?
/*
 * $Id: method.delete_user_attrs.php,v 1.1 2005/12/22 13:44:41 kozlik Exp $
 */

class CData_Layer_delete_user_attrs {
	var $required_methods = array();
	
	/*
	 * delete all user's records from usr_preferences
	 */

	function delete_user_attrs($uid){
	 	global $config;
		
		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		/* table's name */
		$t_name = &$config->data_sql->user_attrs->table_name;
		/* col names */
		$c = &$config->data_sql->user_attrs->cols;
		/* flags */
		$f = &$config->data_sql->user_attrs->flag_values;


		$q = "delete from ".$t_name." 
		      where ".$c->uid."  = '".$uid."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_NOSUCHTABLE) return true;  //expected, table mayn't exist in installed version
			else {ErrorHandler::log_errors($res); return false;}
		}
		return true;
	}
}
?>

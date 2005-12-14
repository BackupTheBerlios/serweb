<?
/*
 * $Id: method.delete_user_missed_calls.php,v 1.2 2005/12/14 16:36:58 kozlik Exp $
 */

class CData_Layer_delete_user_missed_calls {
	var $required_methods = array();

	/*
	 * delete all missed calls of user
	 * if $timestamp is not null delete only calls older than $timestamp
	 */
		
	function delete_user_missed_calls($user, $timestamp, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		/* table's name */
		$t_mc  = &$config->data_sql->missed_calls->table_name;
		/* flags */
		$f_mc = &$config->data_sql->missed_calls->flag_values;

		$q="delete from ".$t_mc." 
			where from_uid='".$user->uuid."'";
		if (!is_null($timestamp)) 
			$q.=" and request_timestamp < '".gmdate("Y-m-d H:i:s", $timestamp)."'";
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		return true;
	}
	
}
?>

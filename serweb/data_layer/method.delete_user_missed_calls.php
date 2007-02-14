<?
/**
 *	@author     Karel Kozlik
 *	@version    $Id: method.delete_user_missed_calls.php,v 1.6 2007/02/14 16:36:38 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	Data layer container holding the method for delete all missed calls of user
 * 
 *	@package    serweb
 */ 
class CData_Layer_delete_user_missed_calls {
	var $required_methods = array();

	/**
	 * delete all missed calls of user
	 * if $timestamp is not null delete only calls older than $timestamp
	 */
		
	function delete_user_missed_calls($uid, $timestamp){
		global $config;

		$errors = array();
		if (!$this->connect_to_db($errors)) {
			ErrorHandler::add_error($errors); return false;
		}

		/* table's name */
		$t_mc  = &$config->data_sql->missed_calls->table_name;
		/* flags */
		$f_mc = &$config->data_sql->missed_calls->flag_values;

		$q="delete from ".$t_mc." 
			where to_uid='".$uid."'";
		if (!is_null($timestamp)) 
			$q.=" and request_timestamp < '".gmdate("Y-m-d H:i:s", $timestamp)."'";
		
		$res=$this->db->query($q);
		if (DB::isError($res)) {
			if ($res->getCode()==DB_ERROR_NOSUCHTABLE) return true;  //expected, table mayn't exist in installed version
			else {ErrorHandler::log_errors($res); return false;}
		}

		return true;
	}
	
}
?>

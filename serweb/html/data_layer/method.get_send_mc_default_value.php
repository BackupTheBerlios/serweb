<?
/*
 * $Id: method.get_send_mc_default_value.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_send_mc_default_value {
	var $required_methods = array();
	
	function get_send_MC_default_value(&$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return -1;
	
		$q="select default_value from ".$config->data_sql->table_user_preferences_types.
			" where att_name='".$config->up_send_daily_missed_calls."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return -1;}

		if (!$row=$res->fetchRow(DB_FETCHMODE_OBJECT)) {$errors[]="not found attribute '".$config->up_send_daily_missed_calls."' in user preferences"; return -1;}
		$default_value=$row->default_value;
		$res->free();

		return $default_value;
	}
	
}
?>

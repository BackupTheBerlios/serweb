<?
/*
 * $Id: method.get_send_mc_list_of_users.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_get_send_mc_list_of_users {
	var $required_methods = array();
	
	function get_send_MC_list_of_users(&$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		if ($config->users_indexed_by=='uuid'){
			$q="select s.username, s.domain, s.uuid, s.email_address, p.value ".
				"from ".$config->data_sql->table_subscriber." s left outer join ".$config->data_sql->table_user_preferences." p ".
						" on s.uuid=p.uuid and p.attribute='".$config->up_send_daily_missed_calls."'";
		}
		else{
			$q="select s.username, s.domain, s.phplib as uuid, s.email_address, p.value ".
				"from ".$config->data_sql->table_subscriber." s left outer join ".$config->data_sql->table_user_preferences." p ".
						" on s.username=p.username and s.domain=p.domain and p.attribute='".$config->up_send_daily_missed_calls."'";
		}

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=$row;
		$res->free();
	
		return $out;

	}
	
}
?>

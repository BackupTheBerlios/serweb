<?
/*
 * $Id: method.update_sip_user_details.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_update_sip_user_details {
	var $required_methods = array();
	
	function update_sip_user_details($user, $email, $allow_find, $timezone, $status_visibility, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$q_upd="";

		if (!is_null($email)){
			$q_upd.=", email_address='".$email."'";
		}

		if (!is_null($status_visibility)){
			$q_upd.=", allow_show_status='".$status_visibility."'";
		}

 		$q="update ".$config->data_sql->table_subscriber.
			" set allow_find='".($allow_find?1:0)."', timezone='".$timezone."', datetime_modified=now()".$q_upd.
			" where ".$this->get_indexing_sql_where_phrase($user);

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		return true;

	}
}
?>

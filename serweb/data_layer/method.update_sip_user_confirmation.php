<?php
/*
 * $Id: method.update_sip_user_confirmation.php,v 1.1 2005/04/26 21:09:44 kozlik Exp $
 */

class CData_Layer_update_sip_user_confirmation {
	var $required_methods = array();
	
	function update_sip_user_confirmation($user, $confirmation, $opt, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

 		$q="update ".$config->data_sql->table_subscriber.
			" set confirmation='".$confirmation."' ".
			" where ".$this->get_indexing_sql_where_phrase($user);

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		
		return true;
	}
}
?>

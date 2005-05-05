<?
/*
 * $Id: method.get_sip_user_details.php,v 1.2 2005/05/05 12:00:03 kozlik Exp $
 */

class CData_Layer_get_sip_user_details {
	var $required_methods = array();
	
	function get_sip_user_details($user, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;
		
		$attributes='';

		$q="select email_address, allow_find, timezone".$attributes." from ".$config->data_sql->table_subscriber.
			" where ".$this->get_indexing_sql_where_phrase($user);
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();
		return $row;	
	}
	
}
?>

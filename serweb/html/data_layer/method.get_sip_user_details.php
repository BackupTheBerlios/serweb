<?
/*
 * $Id: method.get_sip_user_details.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_sip_user_details {
	var $required_methods = array();
	
	function get_sip_user_details($user, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;
		
		$attributes='';
		if ($config->allow_change_status_visibility) $attributes.=', allow_show_status';

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

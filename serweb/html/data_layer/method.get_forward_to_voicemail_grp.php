<?
/*
 * $Id: method.get_forward_to_voicemail_grp.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_forward_to_voicemail_grp {
	var $required_methods = array();
	
	function get_forward_to_voicemail_grp($user, &$errors){
		global $config;
	
		if (!$this->connect_to_db($errors)) return -1;

		$q="select username from ".$config->data_sql->table_grp.
			" where ".$this->get_indexing_sql_where_phrase($user)." and grp='voicemail'";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return -1;}

		if ($res->numRows()) return true;
		else return false;
	}
	
}
?>

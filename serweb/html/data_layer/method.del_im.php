<?
/*
 * $Id: method.del_im.php,v 1.1 2004/08/09 11:40:58 kozlik Exp $
 */

class CData_Layer_del_im {
	var $required_methods = array();
	
	function del_IM($user, $mid, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="delete from ".$config->data_sql->table_message_silo." where mid=".$mid." and ".$this->get_indexing_sql_where_phrase_uri($user);
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		return true;
	}
	
}
?>

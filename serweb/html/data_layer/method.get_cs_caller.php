<?
/*
 * $Id: method.get_cs_caller.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_cs_caller {
	var $required_methods = array();
	
	function get_CS_caller($user, $uri, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="select uri_re, action, param1, param2 from ".$config->data_sql->table_calls_forwarding.
			" where ".$this->get_indexing_sql_where_phrase($user)." and purpose='screening' and uri_re='".$uri."'";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();
		return $row;
	}
	
}
?>

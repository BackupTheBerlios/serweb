<?
/*
 * $Id: method.del_cs_caller.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_del_cs_caller {
	var $required_methods = array();
	
	function del_CS_caller($user, $uri, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="delete from ".$config->data_sql->table_calls_forwarding." where ".
			$this->get_indexing_sql_where_phrase($user)." and purpose='screening' and uri_re='".$uri."'";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		return true;
	}
	
}
?>

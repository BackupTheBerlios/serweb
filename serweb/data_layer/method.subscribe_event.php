<?
/*
 * $Id: method.subscribe_event.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_subscribe_event {
	var $required_methods = array();
	
	function subscribe_event($user, $uri, $desc, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$att=$this->get_indexing_sql_insert_attribs($user);

		$q="insert into ".$config->data_sql->table_event." (uri, description, ".$att['attributes'].") ".
			"values ('".$uri."', '".$desc."', ".$att['values'].")";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		return true;
	}
	
}
?>

<?
/*
 * $Id: method.get_providers.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_get_providers {
	var $required_methods = array();
	
	/* 
	 * return list of prividers 
	 */
	 
	function get_providers(&$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="select id, name from ".$config->data_sql->table_providers;
		$res=$this->db->query($q); 
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=new UP_List_Items($row->name, $row->id);
		$res->free();
		return $out;

	}
	
}
?>

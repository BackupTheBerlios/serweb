<?
/*
 * $Id: method.get_ser_moni_values.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_get_ser_moni_values {
	var $required_methods = array();
	
	function get_ser_moni_values(&$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;
	
		$q="select param, lv, av, mv, ad, min_val, max_val, min_inc, max_inc  from ".$config->data_sql->table_ser_mon_agg;

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
	
		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[$row->param]=$row;
		$res->free();
	
		return $out;
	}
	
}
?>

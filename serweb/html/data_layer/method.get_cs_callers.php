<?
/*
 * $Id: method.get_cs_callers.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_cs_callers {
	var $required_methods = array();
	
	function get_CS_callers($user, $uri, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		if ($uri) $qw=" and uri_re!='$uri' "; else $qw="";

		$q="select uri_re, action, param1, param2 from ".$config->data_sql->table_calls_forwarding.
			" where ".$this->get_indexing_sql_where_phrase($user)." and purpose='screening'".$qw." order by uri_re";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=$row;
		$res->free();
		return $out;
	}
	
}
?>

<?
/*
 * $Id: method.get_cs_callers.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_get_cs_callers {
	var $required_methods = array();
	
	function get_CS_callers($user, $uri, &$errors){
		global $config, $sess;
		
		if (!$this->connect_to_db($errors)) return false;

		if ($uri) $qw=" and uri_re!='$uri' "; else $qw="";

		$q="select uri_re, action, param1, param2 from ".$config->data_sql->table_calls_forwarding.
			" where ".$this->get_indexing_sql_where_phrase($user)." and purpose='screening'".$qw." order by uri_re";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]   = $row;
			$out[$i]['label'] 	  = Ccall_fw::get_label($config->calls_forwarding["screening"], $row['action'], $row['param1'], $row['param2']);
			$out[$i]['url_edit']  = $sess->url("caller_screening.php?kvrk=".uniqID("")."&edit_caller=".rawURLEncode($row['uri_re']));
			$out[$i]['url_dele']  = $sess->url("caller_screening.php?kvrk=".uniqID("")."&dele_caller=".rawURLEncode($row['uri_re']));
		}
		$res->free();
		return $out;

		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=$row;
		$res->free();
		return $out;
	}
	
}
?>

<?
/*
 * $Id: method.get_sd_requests.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_sd_requests {
	var $required_methods = array();
	
	function get_SD_requests($user, $sd, $sd_dom, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		if (!is_null($sd)) $qw=" and (username_from_req_uri!='$sd' or domain_from_req_uri!='$sd_dom') "; else $qw="";

		$q="select username_from_req_uri, domain_from_req_uri, new_request_uri from ".$config->data_sql->table_speed_dial.
			" where ".$this->get_indexing_sql_where_phrase($user).$qw." order by domain_from_req_uri, username_from_req_uri";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		while ($row=$res->fetchRow(DB_FETCHMODE_OBJECT)) $out[]=$row;
		$res->free();
		return $out;
	}
	
}
?>

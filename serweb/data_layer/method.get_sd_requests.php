<?
/*
 * $Id: method.get_sd_requests.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_get_sd_requests {
	var $required_methods = array();
	
	function get_SD_requests($user, $sd, $sd_dom, &$errors){
		global $config,$sess;
		
		if (!$this->connect_to_db($errors)) return false;

		if (!is_null($sd)) $qw=" and (username_from_req_uri!='$sd' or domain_from_req_uri!='$sd_dom') "; else $qw="";

		$q="select username_from_req_uri, domain_from_req_uri, new_request_uri from ".$config->data_sql->table_speed_dial.
			" where ".$this->get_indexing_sql_where_phrase($user).$qw." order by domain_from_req_uri, username_from_req_uri";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		$out=array();
		for ($i=0; $row=$res->fetchRow(DB_FETCHMODE_ASSOC); $i++){
			$out[$i]   = $row;
			$out[$i]['url_edit']  = $sess->url("speed_dial.php?kvrk=".uniqID("")."&edit_sd=".rawURLEncode($row['username_from_req_uri'])."&edit_sd_dom=".rawURLEncode($row['domain_from_req_uri']));
			$out[$i]['url_dele']  = $sess->url("speed_dial.php?kvrk=".uniqID("")."&dele_sd=".rawURLEncode($row['username_from_req_uri'])."&dele_sd_dom=".rawURLEncode($row['domain_from_req_uri']));
		}
		$res->free();
		return $out;
	}
	
}
?>

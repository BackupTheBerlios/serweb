<?
/*
 * $Id: method.get_sd_request.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_get_sd_request {
	var $required_methods = array();
	
	function get_SD_request($user, $sd, $sd_dom, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="select username_from_req_uri, domain_from_req_uri, new_request_uri from ".$config->data_sql->table_speed_dial.
			" where ".$this->get_indexing_sql_where_phrase($user)." and username_from_req_uri='".$sd."' and domain_from_req_uri='".$sd_dom."'";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row=$res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();
		return $row;
	}
	
}
?>

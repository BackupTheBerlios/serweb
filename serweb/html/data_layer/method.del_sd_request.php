<?
/*
 * $Id: method.del_sd_request.php,v 1.1 2004/08/09 11:40:58 kozlik Exp $
 */

class CData_Layer_del_sd_request {
	var $required_methods = array();
	
	function del_SD_request($user, $sd, $sd_dom, &$errors){
		global $config;
		
		if (!$this->connect_to_db($errors)) return false;

		$q="delete from ".$config->data_sql->table_speed_dial." where ".
			$this->get_indexing_sql_where_phrase($user)." and username_from_req_uri='".$sd."' and domain_from_req_uri='".$sd_dom."'";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		return true;
	}
	
}
?>

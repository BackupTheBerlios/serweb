<?
/*
 * $Id: method.domain_exists.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_domain_exists {
	var $required_methods = array();
	
	function domain_exists($domain, &$errors){
	global $config;

		if (!$this->connect_to_db($errors)) return -1;

		$q="select count(*) from ".$config->data_sql->table_domain.
			" where lower(domain) = lower('".addslashes($domain)."')";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return -1;}

		$row = $res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();

		return $row[0]?true:false;

	}
	
}
?>

<?
/*
 * $Id: method.get_uuid_of_user.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_uuid_of_user {
	var $required_methods = array();
	
	/*
	 * return uuid of user with $username and $domain
	 */

	function get_uuid_of_user($username, $domain, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		if ($config->users_indexed_by=='uuid') $attribute='uuid';
		else $attribute='phplib_id';
		
		$q="select ".$attribute." from ". $config->data_sql->table_subscriber.
			" where username='".addslashes($username)."' and domain='".addslashes($domain)."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		if (!$res->numRows()) {return false;}
		$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
		$res->free();

		return $row[$attribute];
	}
	
}
?>

<?
/*
 * $Id: method.is_user_exists.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_is_user_exists {
	var $required_methods = array();
	
	/*
	 *	check if user exists
	 */

	function is_user_exists($uname, $udomain, &$errors){
	 	global $config;

		if (!$this->connect_to_db($errors)) return -1;

		$q="select count(*) from ".$config->data_sql->table_subscriber.
			" where lower(username)=lower('$uname') and lower(domain)=lower('$udomain')";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return -1;}

		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();
		if ($row[0]) return true;

		$q="select count(*) from ".$config->data_sql->table_pending.
			" where lower(username)=lower('$uname') and lower(domain)=lower('$udomain')";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return -1;}

		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();
		if ($row[0]) return true;

		return false;
	}
	
}
?>

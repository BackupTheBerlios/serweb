<?
/*
 * $Id: method.is_user_exists.php,v 1.2 2004/12/10 14:07:46 kozlik Exp $
 */

class CData_Layer_is_user_exists {
	var $required_methods = array();
	
	/*
	 *	check if user exists
	 */

	function is_user_exists($uname, $udomain, &$errors){
	 	global $config;

		if (!$this->connect_to_db($errors)) return -1;

		/* check subscriber table */
		$q="select count(*) from ".$config->data_sql->table_subscriber.
			" where lower(username)=lower('$uname') and lower(domain)=lower('$udomain')";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return -1;}

		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();
		if ($row[0]) return true;

		
		/* check pending table */
		$q="select count(*) from ".$config->data_sql->table_pending.
			" where lower(username)=lower('$uname') and lower(domain)=lower('$udomain')";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return -1;}

		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();
		if ($row[0]) return true;

		
		/* check aliases table */
		if ($config->users_indexed_by=='uuid'){
			$q="select count(*) from ".$config->data_sql->table_uuidaliases.
				" where lower(username)=lower('$uname') and lower(domain)=lower('$udomain')";
		}
		else{
			$q="select count(*) from ".$config->data_sql->table_aliases.
				" where lower(username)=lower('$uname') and lower(domain)=lower('$udomain')";
		}
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return -1;}

		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();
		if ($row[0]) return true;
		
		
		return false;
	}
	
}
?>

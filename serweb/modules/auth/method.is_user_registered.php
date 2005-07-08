<?
/*
 * $Id: method.is_user_registered.php,v 1.1 2005/07/08 11:06:52 kozlik Exp $
 */

class CData_Layer_is_user_registered {
	var $required_methods = array();
	
	/*
	 *	check if user is registered
	 */

	function is_user_registered($user, &$errors){
	 	global $config;

		if (!$this->connect_to_db($errors)) return -1;

		$q="select count(*) from ".$config->data_sql->table_subscriber.
			" where ".$this->get_indexing_sql_where_phrase($user);
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return -1;}

		$row=$res->fetchRow(DB_FETCHMODE_ORDERED);
		$res->free();

		if ($row[0]) return true;

		return false;

	}
	
}
?>

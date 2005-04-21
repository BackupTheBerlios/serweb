<?
/*
 * $Id: method.set_timezone.php,v 1.2 2005/04/21 15:09:46 kozlik Exp $
 */

class CData_Layer_set_timezone {
	var $required_methods = array();
	
	/*
	 * set timezone to timezone of $user
	 */

	function set_timezone($user, &$errors){
		global $config;

		if (!$this->connect_to_db($errors)) return false;

		$q="select timezone from ".$config->data_sql->table_subscriber.
			" where ".$this->get_indexing_sql_where_phrase($user);
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		$row = $res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();

		putenv("TZ=".$row->timezone); //set timezone
		return true;

	}
	
}
?>

<?
/*
 * $Id: method.get_user_real_name.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_user_real_name {
	var $required_methods = array();
	
	 /*
	  * get real name of user
	  */

	function get_user_real_name($user, &$errors){
		global $config;
	
		if (!$this->connect_to_db($errors)) return false;

		$q="select first_name, last_name from ".$config->data_sql->table_subscriber.
			" where ".$this->get_indexing_sql_where_phrase($user);

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}
		if (!$res->numRows()) return false;

		$row = $res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();

		//return $row->first_name." ".$row->last_name." &lt;".$user->uname."@".$user->domain."&gt;";
		return array(
			'fname'=>$row->first_name,
			'lname'=>$row->last_name,
			'uname'=>$user->uname,
			'domain'=>$user->domain);
	}
	
}
?>

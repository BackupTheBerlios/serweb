<?
/*
 * $Id: method.get_user_dom_from_uid.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_get_user_dom_from_uid {
	var $required_methods = array();
	
	/*
	 * get username and domain of user by his uuid
	 * return: username
	 */

	function get_user_dom_from_uid($uid, &$errors){
		global $config, $lang_str;
	
		if (!$this->connect_to_db($errors)) return false;
		
		if ($config->users_indexed_by=='uuid') $attrib='uuid';
		else $attrib='phplib_id';
		
		$q="select username, domain from ". $config->data_sql->table_subscriber.
			" where ".$attrib."='".$uid."'";
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		if (!$res->numRows()) {$errors[]=$lang_str['bad_username']; return false;}
		$row = $res->fetchRow(DB_FETCHMODE_OBJECT);
		$res->free();

		return array('uname'=>$row->username,
		             'domain'=>$row->domain);
	}
}
?>

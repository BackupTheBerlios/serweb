<?
/*
 * $Id: method.get_status_visibility.php,v 1.1 2004/08/09 11:40:59 kozlik Exp $
 */

class CData_Layer_get_status_visibility {
	var $required_methods = array();
	
	/*
	 * get status visibility of sip user
	 * return: true if others can see whether user is online
	 */

	function get_status_visibility($user, $domain, &$errors){
		global $config;

		$q="select allow_show_status from ". $config->data_sql->table_subscriber.
			" where username='".addslashes($user)."' and domain='".addslashes($domain)."'";

		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return -1;}

		if (!$res->numRows()) {return -1;}
		$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
		$res->free();

		return $row['allow_show_status']?true:false;
	}
	
}
?>

<?
/*
 * $Id: method.check_passw_of_user.php,v 1.2 2005/11/01 17:58:40 kozlik Exp $
 */

class CData_Layer_check_passw_of_user {
	var $required_methods = array('get_sql_user_flags');
	
	/*
	 * check if $passw is right password of $user on $domain
	 * return: uuid
	 */

	function check_passw_of_user($user, $domain, $passw, &$errors){
		global $config, $lang_str;

		if (!$this->connect_to_db($errors)) return false;

		if ($config->users_indexed_by=='uuid') $attribute='uuid';
		else $attribute='phplib_id';
		
		$flags = $this->get_sql_user_flags(null);

		if ($config->clear_text_pw) {
			$q="select s.".$attribute.$flags['disabled']['cols'].$flags['deleted']['cols'].
			    " from ". $config->data_sql->table_subscriber." s ".$flags['deleted']['from'].$flags['disabled']['from'].
				" where s.username='".addslashes($user)."' and s.password='".addslashes($passw)."' and s.domain='".addslashes($domain)."'";
		} else {
			$ha1=md5($user.":".$domain.":".$passw);
			$q="select s.".$attribute.$flags['disabled']['cols'].$flags['deleted']['cols'].
			   " from ". $config->data_sql->table_subscriber." s ".$flags['deleted']['from'].$flags['disabled']['from'].
				" where s.username='".addslashes($user)."' and s.domain='".addslashes($domain)."' and s.ha1='".$ha1."'";
		}
		$res=$this->db->query($q);
		if (DB::isError($res)) {
			log_errors($res, $errors); 
			return false;
		}

		if (!$res->numRows()) {
			return false;
		}
		$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
		$res->free();

		if ($row['user_deleted']){
			sw_log("Account '".$user."@".$domain."' is marked as deleted", PEAR_LOG_INFO);
			return false;
		}

		if ($row['user_disabled']) return -1;

		return $row[$attribute];
	}
}
?>

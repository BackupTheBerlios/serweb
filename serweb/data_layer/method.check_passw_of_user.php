<?
/*
 * $Id: method.check_passw_of_user.php,v 1.1 2004/08/25 10:45:58 kozlik Exp $
 */

class CData_Layer_check_passw_of_user {
	var $required_methods = array();
	
	/*
	 * check if $passw is right password of $user on $domain
	 * return: uuid
	 */

	function check_passw_of_user($user, $domain, $passw, &$errors){
		global $config, $lang_str;

		if (!$this->connect_to_db($errors)) return false;

		if ($config->users_indexed_by=='uuid') $attribute='uuid';
		else $attribute='phplib_id';
		
		if ($config->clear_text_pw) {
			$q="select ".$attribute." from ". $config->data_sql->table_subscriber.
				" where username='".addslashes($user)."' and password='".addslashes($passw)."' and domain='".addslashes($domain)."'";
		} else {
			$ha1=md5($uname.":".$config->realm.":".$passw);
			$q="select ".$attribute." from ". $config->data_sql->table_subscriber.
				" where username='".addslashes($user)."' and domain='".addslashes($domain)."' and ha1='".$ha1."'";
		}
		$res=$this->db->query($q);
		if (DB::isError($res)) {log_errors($res, $errors); return false;}

		if (!$res->numRows()) {$errors[]=$lang_str['bad_username']; return false;}
		$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
		$res->free();

		return $row[$attribute];
	}
}
?>

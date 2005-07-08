<?
/*
 * $Id: method.check_passw_of_user.php,v 1.1 2005/07/08 11:08:36 kozlik Exp $
 */

class CData_Layer_check_passw_of_user {
	var $required_methods = array();
	
	/*
	 * check if $passw is right password of $user on $domain
	 * return: uuid
	 */

	function check_passw_of_user($user, $domain, $passw, &$errors){
		global $config;

		if (!$this->connect_to_ldap($errors)) return false;

		$a=&$config->auth_ldap['attrib'];

		$q=array(
			"(".$a['user']."=".addslashes($user.'@'.$domain).") ",

			'base_dn' => $config->auth_ldap['dn'],
			'attributes'=>array($a['uuid'], $a['pass']),
			'action'=>'list'
		);
		$res=$this->ldap->query($q);
		if (DB::isError($res)) {
			//if user is not found
			if ($res->getCode()==DB_ERROR_NOSUCHTABLE) {
				return false;
			}
			else {
				log_errors($res, $errors); 
				return false;
			}
		}

		$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
		$res->free();
		
		// check password
		if ($row[$a['pass']] != $passw) {
			return false;
		}
		
		return $row[$a['uuid']];
	}
}
?>

<?
/*
 * $Id: method.check_credentials.php,v 1.1 2005/12/23 08:59:21 kozlik Exp $
 */

class CData_Layer_check_credentials {
	var $required_methods = array();
	
	/*
	 * check if $passw is right password of $user on $domain
	 * return: uuid
	 */

	function check_credentials($user, $domain, $passw, $opt){
		global $config;

		$errors = array();

		if (!$this->connect_to_ldap($errors)) {
			ErrorHandler::add_error($errors);
			return 0;
		}

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
				return -1;
			}
			else {
				ErrorHandler::log_errors($res); 
				return 0;
			}
		}

		$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
		$res->free();
		
		// check password
		if ($row[$a['pass']] != $passw) {
			return -1;
		}
		
		return $row[$a['uuid']];
	}
}
?>

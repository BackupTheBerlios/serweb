<?
/*
 * $Id: method.check_passw_of_user.php,v 1.1 2005/07/08 11:08:36 kozlik Exp $
 */

require_once 'Auth/RADIUS.php';
require_once 'Crypt/CHAP.php';

class Serweb_Auth_RADIUS_PAP extends Auth_RADIUS_PAP{
	/* Overide function putStandardAttributes to no put any attributes */
    function putStandardAttributes(){
	}
} 

class Serweb_Auth_RADIUS_CHAP_MD5 extends Auth_RADIUS_CHAP_MD5{
	/* Overide function putStandardAttributes to no put any attributes */
    function putStandardAttributes(){
	}
}

class CData_Layer_check_passw_of_user {
	var $required_methods = array();
	
	/*
	 * check if $passw is right password of $user on $domain
	 * return: uuid
	 */

	function check_passw_of_user($user, $domain, $passw, &$errors){
		global $config, $lang_str;

		if ($config->users_indexed_by=='uuid'){
			Die("Module 'auth_radius' is not ready for UUIDized version of serweb. Set \$config->users_indexed_by=='username' in config file");
		}

		if ($config->clear_text_pw){
			$rauth = new Serweb_Auth_RADIUS_PAP($user."@".$domain, $passw);
		}
		else{
		    $crpt = new Crypt_CHAP_MD5();
	    	$crpt->password = $passw;
	
			$rauth = new Serweb_Auth_RADIUS_CHAP_MD5($user."@".$domain, $crpt->challenge, $crpt->chapid);
		    $rauth->response = $crpt->challengeResponse();
		    $rauth->flags = 1;
		}


		foreach($config->auth_radius['host'] as $h){
			$rauth->addServer($h['host'], $h['port'], $h['sharedsecret'], $h['timeout'], $h['maxtries']);
		}


		if (!$rauth->start()){
			log_errors(PEAR::raiseError("Radius: ".$rauth->getError()), $errors); 
			return false;
		}
		
		$result = $rauth->send();
		if (PEAR::isError($result)) {
			log_errors($result, $errors); 
			return false;
		}
	
		if ($result !== true) {
			return false;
		}

		// calculate some UUID - this only for internal use
		// not ready for UUID version
		return md5($user.$domain);
	}
}
?>

<?
/*
 * $Id: method.check_credentials.php,v 1.1 2005/12/23 08:59:21 kozlik Exp $
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

class CData_Layer_check_credentials {
	var $required_methods = array();
	
	/*
	 * check if $passw is right password of $user on $domain
	 * return: uuid
	 */

	/**
	 *	@todo: uuideize
	 *	@todo: change return values to be same as in auth_db
	 */
	function check_credentials($user, $domain, $passw, $opt){
		global $config, $lang_str;

		if ($config->users_indexed_by=='uuid'){
			Die("Module 'auth_radius' is not ready for UUIDized version of serweb.");
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
			ErrorHandler::log_errors(PEAR::raiseError("Radius: ".$rauth->getError())); 
			return false;
		}
		
		$result = $rauth->send();
		if (PEAR::isError($result)) {
			ErrorHandler::log_errors($result); 
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

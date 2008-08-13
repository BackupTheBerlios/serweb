<?
/*
 * $Id: method.check_credentials.php,v 1.4 2008/08/13 11:27:53 kozlik Exp $
 */

require_once 'Auth/RADIUS.php';
require_once 'Crypt/CHAP.php';

class Serweb_Auth_RADIUS_PAP extends Auth_RADIUS_PAP{
	/* Overide function putStandardAttributes to no put any attributes */
    function putStandardAttributes(){
	}

    /**
     * Reads all received attributes after sending the request.
     *
     * This methos stores know attributes in the property attributes, 
     * all attributes (including known attibutes) are stored in rawAttributes 
     * or rawVendorAttributes.
     * NOTE: call this functio also even if the request was rejected, because the 
     * Server returns usualy an errormessage
     *
     * @access public
     * @return bool   true on success, false on error
     */     
    function getAttributes(){
    	return radiusAuthGetAttributes($this);
	}
} 

class Serweb_Auth_RADIUS_CHAP_MD5 extends Auth_RADIUS_CHAP_MD5{
	/* Overide function putStandardAttributes to no put any attributes */
    function putStandardAttributes(){
	}

    /**
     * Reads all received attributes after sending the request.
     *
     * This methos stores know attributes in the property attributes, 
     * all attributes (including known attibutes) are stored in rawAttributes 
     * or rawVendorAttributes.
     * NOTE: call this functio also even if the request was rejected, because the 
     * Server returns usualy an errormessage
     *
     * @access public
     * @return bool   true on success, false on error
     */     
    function getAttributes(){
    	return radiusAuthGetAttributes($this);
	}
}

define('RADIUS_SER_VENDOR', 24960);
define('RADIUS_SER_UID', 17);

function radiusAuthGetAttributes(&$r_obj){
    while ($attrib = radius_get_attr($r_obj->res)) {

        if (!is_array($attrib)) return false;

        $attr = $attrib['attr'];
        $data = $attrib['data'];

        $r_obj->rawAttributes[] = array("attr"=>$attr, "data"=>$data);

        switch ($attr) {
        case RADIUS_VENDOR_SPECIFIC:
		$vavp = radius_get_vendor_attr($data);

		if ($vavp['vendor'] == RADIUS_SER_VENDOR){ 
			if ($vavp['attr'] == RADIUS_SER_UID){
				$r_obj->attributes['ser-attrs']['uid'] = $vavp['data'];
			}
		}
            break;
        }
    }

    return true;
}

class CData_Layer_check_credentials {
	var $required_methods = array();
	
	/**
	 *  Check given credentials and return uid of user (string) if they are 
	 *	correct. If credentials are wrong integer error code is returned:
	 *		 0 - credentials can not be checked (radius error)
	 *		-1 - this tripple (uname, realm, password) not exists
	 *		-2 - this credentials is not for use in serweb
	 *		-3 - account is disabled
	 *		-4 - account is deleted
	 *
	 *  Possible options:
	 *		none
	 *
	 *	@param string $uname	username
	 *	@param string $did	    did
	 *	@param string $realm	realm
	 *	@param string $passw	password
	 *	@param array $opt		associative array of options
	 *	@return mixed			uid or error code
	 */ 
	function check_credentials($uname, $did, $realm, $passw, $opt){
		global $config, $lang_str;

		if ($config->clear_text_pw){
			$rauth = new Serweb_Auth_RADIUS_PAP($uname."@".$realm, $passw);
		}
		else{
		    $crpt = new Crypt_CHAP_MD5();
	    	$crpt->password = $passw;
	
			$rauth = new Serweb_Auth_RADIUS_CHAP_MD5($uname."@".$realm, $crpt->challenge, $crpt->chapid);
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
			sw_log("Radius request rejected for user '".$uname."@".$realm."'", PEAR_LOG_INFO);
			return -1;
		}

		$rauth->getAttributes();

		if (!isset($rauth->attributes['ser-attrs']['uid'])) {
			sw_log("UID is not returned for user '".$uname."@".$realm."'", PEAR_LOG_INFO);
			return -1;
		}

		return $rauth->attributes['ser-attrs']['uid'];
	}
}
?>

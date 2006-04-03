<?
/*
 * $Id: method.check_credentials.php,v 1.2 2006/04/03 14:56:05 kozlik Exp $
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

define('RADIUS_SER_ATTRS', 225);

function radiusAuthGetAttributes(&$r_obj){
    while ($attrib = radius_get_attr($r_obj->res)) {

        if (!is_array($attrib)) return false;

        $attr = $attrib['attr'];
        $data = $attrib['data'];

        $r_obj->rawAttributes[] = array("attr"=>$attr, "data"=>$data);

        switch ($attr) {
        case RADIUS_SER_ATTRS:
        	$attrs = radius_cvt_string($data);
        	$attrs = explode(":", $attrs, 2);
        	
        	if (count($attrs) != 2) break;

        	if (isset($this->attributes['ser-attrs'][$attrs[0]])){
				$at_item = &$r_obj->attributes['ser-attrs'][$attrs[0]];

        		if (is_array($at_item)){
					$at_item[] = $attrs[1];
				}
				else {
					$at_item = array($at_item, $attrs[1]);
				}
			}
			else{
				$r_obj->attributes['ser-attrs'][$attrs[0]] = $attrs[1];
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
	 *	@param string $realm	realm
	 *	@param string $passw	password
	 *	@param array $opt		associative array of options
	 *	@return mixed			uid or error code
	 */ 
	function check_credentials($uname, $realm, $passw, $opt){
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

		return md5($rauth->attributes['ser-attrs']['uid']);
	}
}
?>

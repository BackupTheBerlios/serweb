<?php
/*
 * $Id: local.inc,v 1.18 2006/04/12 13:41:20 kozlik Exp $
 */


/**
 *	main session class
 */
 
class phplib_Session extends Session {
	var $classname = "phplib_Session";
	
	var $trans_id_enabled = false;
	var $cookiename     = "";                ## defaults to classname
	var $mode           = "cookie";          ## We propagate session IDs with cookies
	var $fallback_mode  = "get";
	var $allowcache     = "no";              ## "public", "private", or "no"
	var $lifetime       = 0;                 ## 0 = do session cookies, else minutes
}


/**
 *	default auth class
 */

class phplib_Auth extends Auth {
	var $classname      = "phplib_Auth";
	var $lifetime       = 20;

	/**
	 *	contructor
	 */
	 
	function phplib_Auth(){
		global $config;
		/* call parent's constructor */
		$this->Auth();
		
		$this->lifetime = $config->auth_lifetime;
		$this->auth['adm_domains'] = null;
	}

	/**
	 *	Function is called when user is not authenticated
	 *
	 *	If user is logged in and authentication expired, this function
	 *	display relogin page. Otherwise if user is not logged in yet, is 
	 *	redirected to login page
	 */

	function auth_loginform() {
		global $sess;
		global $_SERWEB;

		$this->auth['adm_domains'] = null;
		
		//user is not logged in, forward to login screen
		if (empty($this->auth["uid"])){
			Header("Location: ".$sess->url("index.php"));
			exit;
		}
		
		//else display relogin form
		include($_SERWEB["serwebdir"] . "relogin.php");
	}

	/**
	 *	Function validate password obtained from re-login form
	 *
	 *	If password is valid, function authenticate user again and return true,
	 *	otherwise return false.
	 *
	 *	@return 	bool
	 */

	function auth_validatelogin() {
	
		$password = "";
		if (isset($_POST['password'])) $password = $_POST['password'];

		if (false === $this->validate_credentials($this->auth['uname'], $this->auth['realm'], $password, array())){
			return false;
		}

		$this->authenticate();

		$perms = $this->find_out_perms($this->auth['uid'], array());
		if (false === $perms) return false;
		$this->set_perms($perms);

		return true;
	}

	/**
	 *	Validate given credentials and return UID if they are valid
	 *
	 *	@static
	 *	@param	string	$username	
	 *	@param	string	$realm		
	 *	@param	string	$password	
	 *	@param	array	$opt		
	 *	@return	string				UID if credentials are valid, false otherwise
	 */

	function validate_credentials($username, $realm, $password, $opt){
		global $lang_str, $data_auth, $config;

		$data_auth->set_xxl_user_id('sip:'.$username.'@'.$realm);
		$data_auth->expect_user_id_may_not_exists();

		$data_auth->add_method('check_credentials');
	
		$opt = array();
		if ($config->clear_text_pw)	{
			$opt['hash'] = 'clear';
			$ha = $password;
		}
		else{
			$opt['hash'] = 'ha1';
			$ha = md5($username.":".$realm.":".$password);
		}

		$uid = $data_auth->check_credentials($username, $realm, $ha, $opt);

		if (is_int($uid) and $uid == -3){
			sw_log("User login: authentication failed: account disabled ", PEAR_LOG_INFO);
			ErrorHandler::add_error($lang_str['account_disabled']);
			return false;
		}

		if (is_int($uid) and $uid <= 0) {
			sw_log("User login: authentication failed: bad username or realm or password ", PEAR_LOG_INFO);
			ErrorHandler::add_error($lang_str['bad_username']);
			return false;
		}


		if (is_null($uid)){
			sw_log("User login: authentication failed: no user ID", PEAR_LOG_INFO);
			ErrorHandler::add_error($lang_str['bad_username']);
			return false;
		}

		return $uid;	
	}

	/**
	 *	Get domain id of domain of the user
	 *	
	 *	@static
	 *	@param	string	$username	
	 *	@param	string	$realm		
	 *	@param	string	$uid	
	 *	@param	array	$opt		
	 *	@return	string				domain ID, FALSE on error
	 */

	function find_out_did($username, $realm, $uid, $opt){
		global $data_auth, $lang_str, $config;

		if (!$config->multidomain) return $config->default_did;

		$data_auth->add_method('get_did_by_realm');
		$data_auth->add_method('get_domain_flags');
		
		$opt = array('check_disabled_flag' => false);
		
		$did = $data_auth->get_did_by_realm($realm, $opt);
		if (false === $did) return false;

		if (is_null($did)){
			sw_log("User login: authentication failed: domain id for realm '".$realm."' not found", PEAR_LOG_INFO);
			ErrorHandler::add_error($lang_str['domain_not_found']);
			return false;
		}

		if (false === $flags = $data_auth->get_domain_flags($did, null)) return false;

		if ($flags['disabled']){
			sw_log("User login: authentication failed: domain with id '".$did."' is disabled", PEAR_LOG_INFO);
			ErrorHandler::add_error($lang_str['account_disabled']);
			return false;
		}

		if ($flags['deleted']){
			sw_log("User login: authentication failed: domain with id '".$did."' is deleted", PEAR_LOG_INFO);
			ErrorHandler::add_error($lang_str['bad_username']);
			return false;
		}


		return $did;
	}

	/**
	 *	Get permissions of user with given UID 
	 *	
	 *	This function return the permissions of user in array
	 *
	 *	@static
	 *	@param	string	$uid
	 *	@param	array	$opt		
	 *	@return	array				array of permissions or FALSE on error
	 */

	function find_out_perms($uid, $opt){
		global $lang_str, $data_auth, $config;

		$an = $config->attr_names;

		$perms = array();

		$attrs = &User_Attrs::singleton($uid);
		
		if (false === $attrib = $attrs->get_attribute($an["is_admin"])) return false;
		if ($attrib) $perms[] = 'admin';

		if (false === $attrib = $attrs->get_attribute($an["is_hostmaster"])) return false;
		if ($attrib) $perms[] = 'hostmaster';

		return $perms;
	}
	
	/**
	 *	Get array of domains administrated by user
	 *	
	 *	@param	string	$uid
	 *	@param	array	$opt		
	 *	@return	array				array of domain IDs or FALSE on error
	 */

	function get_administrated_domains(){
		global $data_auth;
		
		if (!is_null($this->auth['adm_domains'])) return $this->auth['adm_domains'];
		
		$data_auth->add_method('get_domains_of_admin');
		if (false === $domains = $data_auth->get_domains_of_admin($this->auth['uid'], null)) return false;
		
		return $this->auth['adm_domains'] = $domains;
	}
}

/**
 *	@deprec  
 */
class phplib_Pre_Auth extends phplib_Auth {
}


/**
 *	default perm class
 */

class phplib_Perm extends Perm {
	var $classname = "phplib_Perm";

	var $permissions = array(
							"admin"      => 1,
							"change_priv"=> 2,
							"hostmaster" => 4
						);

	/**
	 *	Function is called when permission of user is invalid
	 *
	 *	This function should display page with "permissions invalid" message
	 */
	 
	function perm_invalid($does_have, $must_have) {
		global $_SERWEB;
		include($_SERWEB["serwebdir"] . "perm_invalid.php");
	}
}

?>

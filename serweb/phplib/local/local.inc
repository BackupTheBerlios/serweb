<?php
/*
 * $Id: local.inc,v 1.14 2005/12/01 12:06:12 kozlik Exp $
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
		global $errors;
	
		$password = "";
		if (isset($_POST['password'])) $password = $_POST['password'];

		if (false === $this->validate_credentials($this->auth['uname'], $this->auth['realm'], $password, array(), $errors)){
			return false;
		}
		else{
			$this->authenticate();
			return true;
		}
	}

	/**
	 *	Validate given credentials and return UID if they are valid
	 *
	 *	@static
	 *	@param	string	$username	
	 *	@param	string	$realm		
	 *	@param	string	$password	
	 *	@param	array	$opt		
	 *	@param	array 	$errors		array with error messages
	 *	@return	string				UID if credentials are valid, false otherwise
	 */

	function validate_credentials($username, $realm, $password, $opt, &$errors){
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

		$uid = $data_auth->check_credentials($username, $realm, $ha, $opt, $errors);

		if (is_int($uid) and $uid == -3){
			sw_log("User login: authentication failed: account disabled ", PEAR_LOG_INFO);
			$errors[]=$lang_str['account_disabled'];
			return false;
		}

		if (is_int($uid) and $uid <= 0) {
			sw_log("User login: authentication failed: bad username or realm or password ", PEAR_LOG_INFO);
			$errors[]=$lang_str['bad_username'];
			return false;
		}


		if (is_null($uid)){
			sw_log("User login: authentication failed: no user ID", PEAR_LOG_INFO);
			$errors[]=$lang_str['bad_username'];
			return false;
		}

		return $uid;	
	}

	/**
	 *	Get permissions of user with given UID 
	 *	
	 *	This function return the permissions of user in array
	 *
	 *	@static
	 *	@param	string	$uid
	 *	@param	array	$opt		
	 *	@param	array 	$errors		array with error messages
	 *	@return	array				array of permissions or FALSE on error
	 */

	function find_out_perms($uid, $opt, &$errors){
		global $lang_str, $data_auth;

		$perms = array();

		$data_auth->add_method('get_attribute');
		
		$o = array('uid' => $uid);
		if (false === $attrib = $data_auth->get_attribute("sw_is_admin", $o, $errors)) return false;

		if (is_array($attrib) and $attrib['origin'] == 'user'){ //accept only attribute from user_attrs
			if ($attrib['value']) $perms[] = 'admin';
		}

		if (false === $attrib = $data_auth->get_attribute("sw_is_hostmaster", $o, $errors)) return false;

		if (is_array($attrib) and $attrib['origin'] == 'user'){ //accept only attribute from user_attrs
			if ($attrib['value']) $perms[] = 'hostmaster';
		}
		
		return $perms;
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

<?php
/*
 * $Id: local.inc,v 1.13 2005/11/30 11:17:25 kozlik Exp $
 */



##
## main session class
##

class phplib_Session extends Session {
	var $classname = "phplib_Session";
	
	var $trans_id_enabled = false;
	var $cookiename     = "";                ## defaults to classname
	var $mode           = "cookie";          ## We propagate session IDs with cookies
	var $fallback_mode  = "get";
	var $allowcache     = "no";              ## "public", "private", or "no"
	var $lifetime       = 0;                 ## 0 = do session cookies, else minutes
}


##
## default auth class
##

class phplib_Auth extends Auth {
	var $classname      = "phplib_Auth";
	var $lifetime       = 20;

	function phplib_Auth(){
		global $config;
		/* call parent's constructor */
		$this->Auth();
		
		$this->lifetime = $config->auth_lifetime;
	}

	function auth_loginform() {
		global $sess;
		global $_SERWEB;
		
		//user is not legged in, forward to login screen
		if (empty($this->auth["uid"])){
			Header("Location: ".$sess->url("index.php"));
			exit;
		}
		
		//else display relogin form
		include($_SERWEB["serwebdir"] . "relogin.php");
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

		//@todo: check privileges

		return $uid;	
	}

	function auth_validatelogin() {
		global $config, $errors;
		global $sess, $data_auth, $lang_str;
	
		$password = "";
		if (isset($_POST['password'])) $password = $_POST['password'];

		$data_auth->set_xxl_user_id('sip:'.$this->auth['uname'].'@'.$this->auth['realm']);

		if (false === $this->validate_credentials($this->auth['uname'], $this->auth['realm'], $password, array(), $errors)){
			return false;
		}
		else{
			$this->authenticate();
			return true;
		}
	}

  /*
  	method checks user permisions
  */
	function check_for_privileges(&$user){
		global $config, $errors, $data_auth;

	    if (false === $privileges = $data_auth->get_privileges_of_user($user, array('change_privileges','is_admin', 'hostmaster'), $errors)) return false;

		foreach($privileges as $row) {
			switch ($row->priv_name){
			case 'is_admin':
				if ($row->priv_value) {
					if ($this->auth["perm"]) $this->auth["perm"].=",";
					$this->auth["perm"].="admin";
				}
				break;

			case 'change_privileges':
				if ($row->priv_value) {
					if ($this->auth["perm"]) $this->auth["perm"].=",";
					$this->auth["perm"].="change_priv";
				}
				break;

			case 'hostmaster':
				if ($row->priv_value) {
					if ($this->auth["perm"]) $this->auth["perm"].=",";
					$this->auth["perm"].="hostmaster";
				}
				break;

			}//switch
		}

		/* get privileges to administrative domains */
	    if (false === $user->domains_perm = $data_auth->get_domains_of_admin($user, array(), $errors)) return false;

	} //function check_for_privileges()
}

/**
 *	@deprec  
 */
class phplib_Pre_Auth extends phplib_Auth {
}


##
## default perm class
##

class phplib_Perm extends Perm {
	var $classname = "phplib_Perm";

	var $permissions = array(
							"admin"      => 1,
							"change_priv"=> 2,
							"hostmaster" => 4
						);

	function perm_invalid($does_have, $must_have) {
		global $_SERWEB;
		include($_SERWEB["serwebdir"] . "perm_invalid.php");
	}
}

?>

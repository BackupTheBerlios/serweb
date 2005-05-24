<?php
/*
 * $Id: local.inc,v 1.4 2005/05/24 09:35:23 kozlik Exp $
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
## modified session class used to login when user forgot password
##

class phplib_Session_Pre_Auth extends phplib_Session {

  function get_id($id = "") {

	if ($this->mode=="cookie"){
		/*
		 * if is there cookie and get or post vars then seconds are preffered
		 */
	    if ( "" == $id ) {
          $id = isset($_GET[$this->name]) ?
                $_GET[$this->name] :
                ( isset($_POST[$this->name]) ?
                $_POST[$this->name] :
                "") ;
		}
	    if ( "" == $id ) {
          $id = isset($_COOKIE[$this->name]) ?
                $_COOKIE[$this->name] : "";
		}

		/* set the corect cookie */
	    if ( "" == $id && ( 0 == $this->lifetime )) {
          SetCookie($this->name, $id, 0, "/", $this->cookie_domain);
		}

	}//if

	parent::get_id($id);

  }
}

##
## default auth class
##

class phplib_Auth extends Auth {
  var $classname      = "phplib_Auth";
  var $lifetime       = 20;

  function phplib_Auth(){
  		global $config;
		
		$this->lifetime = $config->auth_lifetime;
  }

  function auth_loginform() {
    global $sess;
    global $_PHPLIB;
	global $config;
	global $errors;
	global $serweb_auth;
	
	//if $serweb_auth is not set, forward to login screen
	if (!isset($serweb_auth) or !$serweb_auth){
        Header("Location: ".$sess->url("index.php"));
		exit;
	}

	//else display relogin form
    include($_PHPLIB["libdir"] . "local/loginform.ihtml");
  }

  function auth_validatelogin() {
    global $config, $_POST, $errors;
	global $serweb_auth, $sess, $data_auth, $lang_str;
	
	if (!$sess->is_registered('serweb_auth')) $sess->register('serweb_auth');

	$password="";
	if (isset($_POST['password'])) $password=$_POST['password'];

	$data_auth->set_xxl_user_id('sip:'.$serweb_auth->uname.'@'.$serweb_auth->domain);

	$p_uid = $data_auth->check_passw_of_user($serweb_auth->uname, $serweb_auth->domain, $password, $errors);

	if ($p_uid) {
		$this->check_for_privileges($serweb_auth);
		return $p_uid;
	}
	else {
		$errors[]=$lang_str['bad_username']; 
		return false;
	}
  }

  /*
  	method checks user permisions
  */
	function check_for_privileges($user){
		global $config, $errors, $data_auth;

	    if (false === $privileges = $data_auth->get_privileges_of_user($user, array('change_privileges','is_admin'), $errors)) return false;

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

			}//switch
		}

	} //function check_for_privileges()
}

class phplib_Pre_Auth extends phplib_Auth {
	var $classname = "phplib_Pre_Auth";

	function auth_preauth()	{
	    global $sess, $errors;
	    global $pre_uid, $pre_uid_expires;
		global $serweb_auth, $config, $data_auth;

		if (!$sess->is_registered('serweb_auth')) $sess->register('serweb_auth');
		
		if (isset($pre_uid)){
			$sess->unregister('pre_uid');
			$sess->unregister('pre_uid_expires');

			if (isset($pre_uid_expires) and $pre_uid_expires<time()) return false;

			if (false === $usr = $data_auth->get_user_dom_from_uid($pre_uid, $errors)) return false;

			$uid=$pre_uid;
			$serweb_auth=new Cserweb_auth($uid, $usr['uname'], $usr['domain']);
			$this->auth["uname"] = $usr['uname'];

			$this->check_for_privileges($serweb_auth);

			return $uid;
		}
		else{
			return false;
		}
	}
}


##
## default perm class
##

class phplib_Perm extends Perm {
  var $classname = "phplib_Perm";

  var $permissions = array(
                            "admin"      => 1,
							"change_priv"=> 2
                          );

  function perm_invalid($does_have, $must_have) {
    global $perm, $auth, $sess;
    global $_PHPLIB;

    include($_PHPLIB["libdir"] . "local/perminvalid.ihtml");
  }
}

?>

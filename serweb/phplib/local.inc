<?php
/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998-2000 NetUSE AG
 *                    Boris Erdmann, Kristian Koehntopp
 *
 * $Id: local.inc,v 1.17 2004/08/09 12:59:36 kozlik Exp $
 *
 */

class DB_phplib extends DB_Sql {
  var $Host;
  var $Database;
  var $User;
  var $Password;

  function DB_phplib(){
  	global $config;
	$this->Host     = $config->data_sql->host[0]['host'];
	$this->Database = $config->data_sql->host[0]['name'];
	$this->User     = $config->data_sql->host[0]['user'];
	$this->Password = $config->data_sql->host[0]['pass'];
  }
}


##
## Session needs to use a storage container (ct).
## Select exactly one of the following and set $that_class
## in Example_Session appropriately.
##

class phplib_CT_Sql extends CT_Sql {
  var $database_class = "DB_phplib";          ## Which database to connect...
  var $database_table = "active_sessions"; ## and find our session data in this table.
}

##
## main session class
##

class phplib_Session extends Session {
  var $classname = "phplib_Session";

  var $cookiename     = "";                ## defaults to classname
  var $magic          = "Hocuspocus";      ## ID seed
  var $mode           = "cookie";          ## We propagate session IDs with cookies
  var $fallback_mode  = "get";
  var $lifetime       = 0;                 ## 0 = do session cookies, else minutes
  var $that_class     = "phplib_CT_Sql";  ## name of data storage container class
  var $gc_probability = 5;
  var $allowcache     = "no";              ## "public", "private", or "no"
}

##
## modified session class used to login when user forgot password
##

class phplib_Session_Pre_Auth extends phplib_Session {

  function get_id($id = "") {
    global $HTTP_COOKIE_VARS, $HTTP_GET_VARS, $HTTP_POST_VARS, $QUERY_STRING;

	if ($this->mode=="cookie"){
		/*
		 * if is there cookie and get or post vars then seconds are preffered
		 */
	    if ( "" == $id ) {
          $id = isset($HTTP_GET_VARS[$this->name]) ?
                $HTTP_GET_VARS[$this->name] :
                ( isset($HTTP_POST_VARS[$this->name]) ?
                $HTTP_POST_VARS[$this->name] :
                "") ;
		}
	    if ( "" == $id ) {
          $id = isset($HTTP_COOKIE_VARS[$this->name]) ?
                $HTTP_COOKIE_VARS[$this->name] : "";
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
  var $data;

  function phplib_Auth(){
		if (!$data = CData_Layer::create($errors)) die($errors[0]);
		$this->data=$data;
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
    include($_PHPLIB["libdir"] . "loginform.ihtml");
  }

  function auth_validatelogin() {
    global $config, $_POST, $errors;
	global $serweb_auth, $sess;
	
	if (!$sess->is_registered('serweb_auth')) $sess->register('serweb_auth');

	$password="";
	if (isset($_POST['password'])) $password=$_POST['password'];

	$p_uid = $this->data->check_passw_of_user($serweb_auth->uname, $serweb_auth->domain, $password, $errors);

	if ($p_uid) {
		$this->check_for_privileges($serweb_auth);
		return $p_uid;
	}
	else return false;
  }

  /*
  	method checks user permisions
  */
	function check_for_privileges($user){
		global $config, $errors;

	    if (false === $privileges = $this->data->get_privileges_of_user($user, array('change_privileges','is_admin'), $errors)) return false;

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
		global $serweb_auth, $config;

		if (!$sess->is_registered('serweb_auth')) $sess->register('serweb_auth');
		
		if (isset($pre_uid)){
			$sess->unregister('pre_uid');
			$sess->unregister('pre_uid_expires');

			if (isset($pre_uid_expires) and $pre_uid_expires<time()) return false;

			if (false === $usr = $this->data->get_user_dom_from_uid($pre_uid, $errors)) return false;

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

    include($_PHPLIB["libdir"] . "perminvalid.ihtml");
  }
}

?>
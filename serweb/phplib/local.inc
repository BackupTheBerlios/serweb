<?php
/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998-2000 NetUSE AG
 *                    Boris Erdmann, Kristian Koehntopp
 *
 * $Id: local.inc,v 1.10 2003/10/13 18:49:03 kozlik Exp $
 *
 */ 

class DB_phplib extends DB_Sql {
  var $Host;
  var $Database;
  var $User;
  var $Password;
  
  function DB_phplib(){
  	global $config;
	$this->Host     = $config->db_host;
	$this->Database = $config->db_name;
	$this->User     = $config->db_user;
	$this->Password = $config->db_pass;
  }
}

class DB_ppaid extends DB_Sql {
  var $Host;
  var $Database;
  var $User;
  var $Password;
  
  function DB_ppaid(){
  	global $config;
	$this->Host     = $config->ppaid->db_host;
	$this->Database = $config->ppaid->db_name;
	$this->User     = $config->ppaid->db_user;
	$this->Password = $config->ppaid->db_pass;
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

  var $lifetime       = 15;

  var $database_class = "DB_phplib";
  var $database_table;
  
  function phplib_Auth(){
  	global $config;
	$this->database_table = $config->table_subscriber;
  }
  
  function auth_loginform() {
    global $sess;
    global $_PHPLIB;

    include($_PHPLIB["libdir"] . "loginform.ihtml");
  }
  
  function auth_validatelogin() {
    global $username, $password, $config;

    if(isset($username)) {
      $this->auth["uname"]=$username;        ## This provides access for "loginform.ihtml"
    }
    
    $uid = false;

	if ($config->clear_text_pw){
	    $this->db->query(sprintf("select phplib_id, perms ".
    	                         "        from %s ".
        	                     "       where username = '%s' ".
            	                 "         and password = '%s'".
								 "         and domain = '%s'",
                	          $this->database_table,
                    	      addslashes($username),
                        	  addslashes($password),
							  addslashes($config->realm)));
	}else{
		$ha1=md5($username.":".$config->realm.":".$password);
    	
	    $this->db->query(sprintf("select phplib_id, perms ".
    	                         "        from %s ".
        	                     "       where username = '%s' ".
								 "        and domain = '%s'".
            	                 "         and ha1 = '%s'",
                	          $this->database_table,
                    	      addslashes($username),
							  addslashes($config->realm),
                        	  addslashes($ha1)));
	}
							

    while($this->db->next_record()) {
      $uid = $this->db->f("phplib_id");
      $this->auth["perm"] = $this->db->f("perms");
    }
    return $uid;
  }
}

class phplib_Pre_Auth extends phplib_Auth {
	var $classname = "phplib_Pre_Auth";

	function auth_preauth()	{
	    global $sess;
	    global $pre_uid, $pre_uid_expires;

		$sess->unregister('pre_uid');
		$sess->unregister('pre_uid_expires');

		if (isset($pre_uid_expires) and $pre_uid_expires<time()) return false;
		
	    $this->db->query(sprintf("select username, perms ".
	                             "        from %s ".
	                             "       where phplib_id = '%s' ",
	                          $this->database_table,
	                          addslashes($pre_uid)));

	    $uid=0;
	    while($this->db->next_record())	{
	      $this->auth["uname"] = $this->db->f("username");
	      $this->auth["perm"] = $this->db->f("perms");
		  $uid=$pre_uid;
	    }

		return $uid;
	}
}

##
## classes for authenticate resellers
##

class phplib_resell_auth extends phplib_Auth {
  var $classname      = "phplib_resell_auth";

  var $database_class = "DB_ppaid";

  function phplib_resell_Auth(){
  	global $config;
	$this->database_table = $config->ppaid->r_table;
  }

  function auth_validatelogin() {
    global $username, $password, $config;

    if(isset($username)) {
      $this->auth["uname"]=$username;        ## This provides access for "loginform.ihtml"
    }
    
    $uid = false;

    $this->db->query(sprintf("select %s ".
   	                         "        from %s ".
       	                     "       where %s = '%s' ".
           	                 "         and %s = '%s'",
						  $config->ppaid->r_phplib_id,
               	          $this->database_table,
						  $config->ppaid->r_id,
                   	      addslashes($username),
						  $config->ppaid->r_pass,
                       	  addslashes($password)));

    while($this->db->next_record()) {
      $uid = $this->db->f($config->ppaid->r_phplib_id);
      $this->auth["perm"] = ($username==$config->ppaid->operator_id)?"operator":"";
    }
    return $uid;
  }
}


class phplib_resell_Pre_Auth extends phplib_resell_auth {
	var $classname = "phplib_resell_Pre_Auth";

	function auth_preauth()	{
	    global $sess, $config;
	    global $pre_uid, $pre_uid_expires;

		$sess->unregister('pre_uid');
		$sess->unregister('pre_uid_expires');

		if (isset($pre_uid_expires) and $pre_uid_expires<time()) return false;

	    $this->db->query(sprintf("select %s ".
	                             "        from %s ".
	                             "       where %s = '%s' ",
							  $config->ppaid->r_id,
	                          $this->database_table,
							  $config->ppaid->r_phplib_id,
	                          addslashes($pre_uid)));

	    $uid=0;
	    while($this->db->next_record())	{
	      $this->auth["uname"] = $this->db->f($config->ppaid->r_id);
	      $this->auth["perm"] = ($this->auth["uname"]==$config->ppaid->operator_id)?"operator":"";
		  $uid=$pre_uid;
	    }

		return $uid;
	}
}

##
## default perm class
##

class phplib_Perm extends Perm {
  var $classname = "phplib_Perm";
  
  var $permissions = array(
                            "admin"      => 1
                          );

  function perm_invalid($does_have, $must_have) {
    global $perm, $auth, $sess;
    global $_PHPLIB;
    
    include($_PHPLIB["libdir"] . "perminvalid.ihtml");
  }
}


##
## Perm subclass used in ppaid module
##

class phplib_resell_Perm extends Perm {
  var $classname = "phplib_resell_Perm";
  
  var $permissions = array(
                            "operator"      => 1
                          );

  function perm_invalid($does_have, $must_have) {
    global $perm, $auth, $sess;
    global $_PHPLIB;
    
    include($_PHPLIB["libdir"] . "perminvalid.ihtml");
  }
}


?>

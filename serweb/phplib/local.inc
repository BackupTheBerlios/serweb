<?php
/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998-2000 NetUSE AG
 *                    Boris Erdmann, Kristian Koehntopp
 *
 * $Id: local.inc,v 1.14 2004/03/24 21:21:39 kozlik Exp $
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
	global $config;

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
	
	if ($this->auth["perm"]=='admin'){
		$this->check_for_privilege_change_privileges();
	}
	
    return $uid;
  }

  /*
  	method checks if user have privilege 'change_privileges'
  */
  function check_for_privilege_change_privileges(){
    global $username, $config;

    $this->db->query(sprintf("select priv_value ".
   	                         "        from %s ".
       	                     "       where username = '%s' ".
							 "        and domain = '%s'".
           	                 "        and priv_name = 'change_privileges'",
               	          $config->table_admin_privileges,
                   	      addslashes($this->auth["uname"]),
						  addslashes($config->realm)));

    while($this->db->next_record()) {
		if ($this->db->f("priv_value")) {
			if ($this->auth["perm"]) $this->auth["perm"].=",";
			$this->auth["perm"].="change_priv";
		}
    }
  }
}

class phplib_Pre_Auth extends phplib_Auth {
	var $classname = "phplib_Pre_Auth";

	function auth_preauth()	{
	    global $sess;
	    global $pre_uid, $pre_uid_expires;

		if (isset($pre_uid)){
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
	
			if ($this->auth["perm"]=='admin'){
				$this->check_for_privilege_change_privileges();
			}
			
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

<?
/*
 * $Id: data_layer.php,v 1.6 2005/04/21 15:09:46 kozlik Exp $
 */

// variable $_data_layer_required_methods should be defined at beginning of each php script
// $_data_layer_required_methods=array();
 
class CData_Layer{

	var $db=null;						//PEAR DB object
	var $ldap=null;						//PEAR DB LDAP object
	var $rpc=null;						//PEAR XML_RPC object

	var $act_row=0, $num_rows=0;		//used when result returns too many rows
	var $showed_rows;					//how many rows from result display

	var $_data_layer_loaded_methods=array();										

	var $xxl_user_id = null;

	var $xxl_proxy = null;
	var $xxl_db_dsn = null;
	
	var $name = "auth_user";			//name of instance

	/*
	 *   Constructor
	 */
	 
	function CData_Layer(){
		global $config;
		$this->showed_rows = &$config->num_of_showed_items;
	}

    /**
     *	Create a new DataLayer object and agregate aditional methods
     *
     *	@param array $errors			array containing error messages
     *	@return object CData_Layer		instance of CData_Layer class
     *	@static
     *	@access public 
	 */

	function &create(&$errors){
		global $config;

		$obj = &new CData_Layer();

		$obj->require_and_agregate_methods();
		return $obj;
	}

    /**
     *	Attempts to return a reference to a concrete CData_Layer instance of name
     *	$instance_name, only creating a new instance if no data_layer instance with 
	 *	the same name currently exists.
     * 
     *	@param string $instance_name	name of data_layer instance, if empty "auth_user" is used
     *	@param array $errors			array containing error messages
     *	@return object CData_Layer		instance of CData_Layer class
     *	@static
     *	@access public 
	 */
	function &singleton($instance_name, &$errors){
		static $instances = array();

		if (!$instance_name) $instance_name = "auth_user";

		if (!isset($instances[$instance_name])){
			$instances[$instance_name] = &CData_Layer::create($errors);
			$instances[$instance_name]->name = $instance_name;
		}
		
		return $instances[$instance_name];
	}


	/**
	 *   dynamicaly agregate aditional methods
	 *   $m is string or array
	 */

	function add_method($m){
		global $_data_layer_required_methods;

		if (is_array($m)) 
			$_data_layer_required_methods = array_merge($_data_layer_required_methods, $m);
		else
			$_data_layer_required_methods[] = $m;
			
		$this->require_and_agregate_methods();
	}
										
	/**
	 *   dynamicaly agregate aditional methods
	 */

	function require_and_agregate_methods(){
		global $_data_layer_required_methods;
		global $_SERWEB;
		global $config;
		
		if (!isset($_data_layer_required_methods) or !is_array($_data_layer_required_methods)) 
			$_data_layer_required_methods = array();
			
		$_data_layer_required_methods = array_merge($config->data_layer_always_required_functions, $_data_layer_required_methods);
		
		reset($_data_layer_required_methods);
		while (list(, $item) = each($_data_layer_required_methods)) {
		
			if (false ===  array_search($item, $this->_data_layer_loaded_methods)){ //if required method isn't loaded yet, load it
			
				//require class with method definition
				if (file_exists($_SERWEB["serwebdir"] . "../data_layer/customized/method.".$item.".php")) 
					//if exists customized version of method, require it
					require_once ($_SERWEB["serwebdir"] . "../data_layer/customized/method.".$item.".php");
				else
					//otherwise require default version
					require_once ($_SERWEB["serwebdir"] . "../data_layer/method.".$item.".php");	
					
				//agregate methods of required class to this object
				aggregate_methods($this, "CData_Layer_".$item);								
				
				//add method to $_data_layer_loaded_methods array
				$this->_data_layer_loaded_methods[] = $item;										
				
				//add methods required by currently loaded method to $_data_layer_required_methods array
				$class_vars = get_class_vars("CData_Layer_".$item);							
				if (isset($class_vars['required_methods']) and is_array($class_vars['required_methods'])){
					$_data_layer_required_methods = array_merge($_data_layer_required_methods, $class_vars['required_methods']);
				}
			}
		}
	}

	/**
	 *	Ask SER cluster on proxy which is assignet to user
	 *
	 *	@param String $user_id		sip uri of iser or urn with uuid of user (format of urn is: 'urn:uuid:foobar')
	 *
	 *	@return String 	sip uri of proxy to which is user assignet or FALSE on error
	 *
	 *	@todo test if this function work correctly and remove testing return statement
	 */	
	function lookup_proxy($user_id){
		global $config;

		$cli = new XML_RPC_Client('/'.$user_id, $config->ser_rpc['host'], $config->ser_rpc['port']);
		
		$params = array(new XML_RPC_Value($user_id, 'string'));
		$msg = new XML_RPC_Message_patched('get_proxy', $params);
		$res = $cli->send($msg);

		if ($this->rpc_is_error($res, $cli)){
			log_errors($res, $errors); return false;
		}

	    $val = $res->value();
		$proxy_uri = trim($val->scalarval());

		if (!$proxy_uri){
			sw_log("Lookup_proxy: invalid answer from get_proxy", PEAR_LOG_ERR);
			return false;
		}

		sw_log("Lookup_proxy: ".$proxy_uri." assigned to user ".$user_id, PEAR_LOG_DEBUG);
			


		$cli = new XML_RPC_Client('/'.$proxy_uri, $config->ser_rpc['host'], $config->ser_rpc['port']);

		$msg = new XML_RPC_Message_patched('get_db_uri');
		$res = $cli->send($msg);

		if ($this->rpc_is_error($res, $cli)){
			log_errors($res, $errors); return false;		}

	    $val = $res->value();
		$db_uri = trim($val->scalarval());

		if (!$db_uri){
			sw_log("Lookup_proxy: invalid answer from get_db_uri", PEAR_LOG_ERR);
			return false;
		}

		sw_log("Lookup_proxy: ".$db_uri." assigned to proxy ".$proxy_uri, PEAR_LOG_DEBUG);

			
		return array('proxy' => $proxy_uri,
           			 'db' => $db_uri);
	}

	/**
	 *	Set session variable $sess_xxl_proxy 
	 *	by uri returned from function lookup_proxy()
	 *
	 *	@param string $sip_uri
	 *	@return bool	TRUE on success, FALSE on bad format of $sip_uri
	 *	@access private
	 */
	function set_sess_xxl_proxy($proxy){
		global $config, $sess_xxl_proxy;
		
		/* get instance of Creg class */
		$reg = Creg::singleton();
		
		$sess_xxl_proxy[$this->name] = $proxy;
		
		/* parse parameters from sip uri of proxy */
		$params = $reg->get_parameters($proxy['proxy']);
		
		if (isset($params['expire'])) {
			/* if sip uri contain parameter 'expire', use it for calculate new expiration time */
			$sess_xxl_proxy[$this->name]['expire'] = time() + $params['expire'];
			sw_log("Expiration time of proxy assigment is set by uri parameter to ".$params['expire']." seconds", PEAR_LOG_DEBUG);
		}
		else {
			/* else use config variable for calculation expiration time */
			$sess_xxl_proxy[$this->name]['expire'] = time() + $config->XXL_proxy_asigment_lifetime;
			sw_log("Expiration time of proxy assigment is set by config variable to ".$config->XXL_proxy_asigment_lifetime." seconds", PEAR_LOG_DEBUG);
		}
		
		return true;
	}

	/**
	 *	Set user id for proxy lookup in XXL version. 
	 *	Calling this function force call proxy_lookup function even though
	 *	proxy url is known
	 *
	 *	@param String $user_id		sip uri of iser or urn with uuid of user (format of urn is: 'urn:uuid:foobar')
	 */
	function set_xxl_user_id($user_id){
		$this->xxl_user_id = $user_id;
	}

	/**
	 *	Return hostname of DB server
	 *	
	 *	@return string 		DB host or FALSE on error
	 *	@access private
	 */
	function find_xxl_proxy(){
		global $sess, $sess_xxl_proxy, $serweb_auth, $config;
		
		if (!$sess->is_registered('sess_xxl_proxy')) $sess->register('sess_xxl_proxy');
		if (!isset($sess_xxl_proxy[$this->name])) 
			$sess_xxl_proxy[$this->name] = array('proxy' => null,
		        	                             'db' => null,
												 'expire' => 0);


		// if $this->xxl_user_id is set, force calling lookup_proxy
		if ($this->xxl_user_id){
			if (false === $proxy = $this->lookup_proxy($this->xxl_user_id)){
				return false;
			}
			
			if (!$this->set_sess_xxl_proxy($proxy)) return false;
			return $sess_xxl_proxy[$this->name];
		}

		// if db host is in session and not expired yet, return it
		if ($sess_xxl_proxy[$this->name] and 
				(!isset($sess_xxl_proxy[$this->name]['expire']) or 
				 $sess_xxl_proxy[$this->name]['expire'] > time())){
			return $sess_xxl_proxy[$this->name];
		}

		// if $serweb_auth exists use it for obtain uri of sip proxy
		if (isset($serweb_auth->uuid) and $serweb_auth->uuid){

			if ($config->users_indexed_by=='uuid'){
				$user_id = 'urn:uuid:'.$serweb_auth->uuid;
			} else {
				$user_id = 'sip:'.$serweb_auth->uname."@".$serweb_auth->domain;
			}

			if (false === $proxy = $this->lookup_proxy($user_id)){
				return false;
			}
			
			if (!$this->set_sess_xxl_proxy($proxy)) return false;
			return $sess_xxl_proxy[$this->name];
		}

		sw_log("Can't find id of user to give it to function lookup_proxy", PEAR_LOG_ERR);
		return false;
	}

	/**
	 *	Set internal variables $xxl_proxy and $xxl_db_dsn
	 *
	 *	@return bool FALSE on error
	 *	@access private
	 */
	 
	function set_xxl_connection(&$errors){
		//get url of DB host which should be used 
		if (false === $proxy = $this->find_xxl_proxy()){
			log_errors(PEAR::raiseError("Can't get URL of DB server"), $errors); return false; 
		}
			
		$this->xxl_proxy = $proxy['proxy'];
		$this->xxl_db_dsn = $proxy['db'];
		
		return true;
	}


	/**
	 *	Set proxy to which should be this instance connected
	 *	@access public
	 */
	 
	function set_xxl_proxy(&$proxy){

		if (!isset($proxy['db'])){
			if (false === $db_uri = $this->get_db_uri($proxy['proxy'])) return false;
			$proxy['db'] = $db_uri;
		}

		$this->xxl_proxy = $proxy['proxy'];
		$this->xxl_db_dsn = $proxy['db'];
		
		return true;
	}


	/**
	 *	Get proxy to which is this instance connected
	 *	@return string	sip uri of proxy or FALSE on error
	 *	@access public
	 */

	function get_xxl_proxy(&$errors){
		global $config;
		
		if ($config->enable_XXL){
			/* if not set proxy to connect, get it */
			if (! $this->xxl_proxy){
				if(false === $this->set_xxl_connection($errors)) {
					return false;
				}
			}
			
			return $this->xxl_proxy;
		}
		else
			return "sip:".$config->ser_rpc['host'];
	}


	/**
	 *	Get dsn of DB used by given sip proxy
	 *
	 *	@param string $proxy_uri	uri of sip proxy
	 *	@return string				dsn of DB
	 */
	function get_db_uri($proxy_uri){
		if (!$this->connect_to_xml_rpc($proxy_uri, $errors)) return false;

		$msg = new XML_RPC_Message_patched('get_db_uri');
		
		$res = $this->rpc->send($msg);

		if ($this->rpc_is_error($res)){
			log_errors($res, $errors); return false;		}
		
	    $val = $res->value();
		$val = trim($val->scalarval());

		sw_log("Get_db_uri for: ".$proxy_uri." returned: ".$val, PEAR_LOG_DEBUG);
			
		return $val;
	}

	/**
	 *	Check if there was error during xml_rpc request
	 *
	 *	@param object $resp		result of XML_RPC_Client::send
	 *	@param object $client	instance of XML_RPC_Client, $this->rpc is used by default
	 *	@return bool			true on error, false on everything is OK
	 */
	function rpc_is_error(&$resp, $client=null){
		if (!$resp) {
			if (!$client) $client = &$this->rpc;
		
			$resp = PEAR::raiseError("xml_rpc communication error",
									 null, null, null, $client->errstr);
			return true;
		}
		
		if ($resp->faultCode()){
			$resp = PEAR::raiseError("xml_rpc request error",
									 null, null, null, $resp->faultCode().":".$resp->faultString());
			return true;
		}

	    $val = $resp->value();
		$val = $val->scalarval();
		if (is_numeric(substr($val, 0, 3))){
			if (substr($val, 0, 1) >= 4){
				$lines = explode("\n", $val);
				
				$resp = PEAR::raiseError("xml_rpc request error",
										 null, null, null, $lines[0]);
				return true;
			}
		}

		return false;	
	}

	/**
	 *	connect to sql database
	 */

	function connect_to_db(&$errors){
		global $config;

		if ($this->db) return $this->db;

		$cfg=&$config->data_sql;

		if ($config->enable_XXL){
			//use only first definition of DB host
			$serv=0;
			$num=1;

			//if not set DSN of DB to connect, get it
			if (! $this->xxl_db_dsn){
				if(false === $this->set_xxl_connection($errors)) return false;
			}
			
		}
		else{
			$num=count($cfg->host); //get number of SQL servers that we can use

			if ($num>1)	$serv=mt_rand(0, $num-1);
			else $serv=0;
		}
		
		$tries=0;
		do{		
			$cont=0;
			if ($config->enable_XXL){
				$dsn = $this->xxl_db_dsn;
			}
			else{
				$dsn = 	$cfg->type."://".
						$cfg->host[$serv]['user'].":".
						$cfg->host[$serv]['pass']."@".
						$cfg->host[$serv]['host'].
							(empty($cfg->host[$serv]['port'])?
								"":
								":".$cfg->host[$serv]['port'])."/".
						$cfg->host[$serv]['name'];
			}
	
			$db = DB::connect($dsn, true);
	
			if (DB::isError($db)) {	
				//if connect failed and multiple servers is defined
				if (($db->getCode() == DB_ERROR_CONNECT_FAILED) and ($num>1)){ 
					//try another server
					$tries++;
					$serv++; $serv %= $num;
					if ($tries<$num) {
						$cont=1; continue;
					}
				}
				log_errors($db, $errors); return false; 
			}
		}while($cont);

		$this->db=$db;
		return $db;
	}

	/**
	 *	create instance of xml rpc client 
	 */
	function connect_to_xml_rpc($user_uri, &$errors){
		global $config;

		if (!$user_uri){
			$user_uri = "sip:".$config->ser_rpc['host'];
		}


		if ($this->rpc and $this->rpc->path == '/'.$user_uri) return $this->rpc;


		if ($config->enable_XXL){

			/* if not set proxy to connect, get it */
			if (! $this->xxl_proxy){
				if(false === $this->set_xxl_connection($errors)) return false;
			}

			/* get instance of Creg class */
			$reg = Creg::singleton();
			
			$proxy_host = $reg->get_domainname($this->xxl_proxy);

			$rpc = new XML_RPC_Client('/'.$user_uri, $proxy_host, $config->ser_rpc['port']);
			
		}
		else{
			$rpc = new XML_RPC_Client('/'.$user_uri, $config->ser_rpc['host'], $config->ser_rpc['port']);
			
		}

		$this->rpc=&$rpc;
		return $rpc;
	}

	
	/**
	 *	connect to LDAP
	 */
	
	function connect_to_ldap(&$errors){
		global $config;

		if ($this->ldap) return $this->ldap;

		$cfg=&$config->data_ldap;

		$num=count($cfg->host); //get number of LDAP servers that we can use

		if ($num>1)	$serv=mt_rand(0, $num-1);
		else $serv=0;
		
		$tries=0;
		do{		
			$cont=0;
		
			if ($cfg->version==2) $dsn = "ldap2";
			else if ($cfg->version==3) $dsn = "ldap3";
			else $dsn = "ldap";
	
			$dsn .=	"://".
					$cfg->host[$serv]['login_dn'].":".
					$cfg->host[$serv]['login_pass']."@".
					$cfg->host[$serv]['host'].
						(empty($cfg->host[$serv]['port'])?
							"":
							":".$cfg->host[$serv]['port'])."/".
					$cfg->base_dn;
	
			$ldap = DB::connect($dsn, true);
	
			if (DB::isError($ldap)) {	
				//if connect failed and multiple servers is defined
				if (($ldap->getCode() == DB_ERROR_CONNECT_FAILED) and ($num>1)){ 
					//try another server
					$tries++;
					$serv++; $serv %= $num;
					if ($tries<$num) {
						$cont=1; continue;
					}
				}
				log_errors($ldap, $errors); return false; 
			}
		}while($cont);
			
			
		$this->ldap=$ldap;
		return $ldap;
	}



	function set_num_rows($num_rows){
		$this->num_rows=$num_rows;
	}

	function get_num_rows(){
		return $this->num_rows;
	}

	function set_act_row($act_row){
		$this->act_row=$act_row;
	}

	function get_act_row(){
		return $this->act_row;
	}

	function get_showed_rows(){
		return $this->showed_rows;
	}

	function set_showed_rows($showed_rows){
		$this->showed_rows=$showed_rows;
	}

	function get_res_from(){
		return $this->get_act_row()+1;
	}

	function get_res_to(){
		global $config;
		return ((($this->get_act_row()+$this->get_showed_rows())<$this->get_num_rows())?
				($this->get_act_row()+$this->get_showed_rows()):
				$this->get_num_rows());
	}

	
	/* if act_row is bigger then num_rows, correct it */
	function correct_act_row(){
		if ($this->get_act_row() >= $this->get_num_rows()) 
			$this->set_act_row(max(0, $this->get_num_rows()-$this->get_showed_rows()));
	}
	
	
	/* return where phrase for sql commands depending on how are user's indexed */
	
	function get_indexing_sql_where_phrase($user, $uuid_col='uuid', $uname_col='username', $domain_col='domain'){
		global $config;
		if ($config->users_indexed_by=='uuid') 
			return $uuid_col."='".$user->uuid."'";
		else 
			return "(".$uname_col."='".addslashes($user->uname)."' and ".$domain_col."='".addslashes($user->domain)."')";
	}

	/* return where phrase for sql commands depending on how are user's indexed - tables where users are indexed by sip uri */

	function get_indexing_sql_where_phrase_uri($user, $uuid_col='uuid', $r_uri_col='r_uri'){
		global $config;
		if ($config->users_indexed_by=='uuid') 
			return $uuid_col."='".$user->uuid."'";
		else 
			return $r_uri_col." like 'sip:".$user->uname."@".$user->domain."%'";
	}
	

	/* return attributes and values for sql insert commands depending on how are user's indexed */

	function get_indexing_sql_insert_attribs($user, $uuid_col='uuid', $uname_col='username', $domain_col='domain'){
		global $config;

		if ($config->users_indexed_by=='uuid') {
			$attributes=$uuid_col;
			$values="'".$user->uuid."'";
		}
		else{
			$attributes=$uname_col.", ".$domain_col;
			$values="'".addslashes($user->uname)."', '".addslashes($user->domain)."'";
		}

		return array('attributes'=>$attributes, 'values'=>$values);
	}

	/* return filter for ldap commands depending on how are user's indexed */
	
	function get_indexing_ldap_filter($user, $uuid_col='uuid', $uname_col='username', $domain_col='domain'){
		global $config;
		if ($config->users_indexed_by=='uuid') return "(".$uuid_col."=".$user->uuid.")";
		else return "(&(".$uname_col."=".$user->uname.") ".
			          "(".$domain_col."=".$user->domain."))";
	
	}

	/* function generates error message when some data container is not implemented for some type of data */

	function not_implemented(){
		global $config;

		$backtrace=debug_backtrace();

		$err = PEAR::raiseError("Possible bad configuration. Function '".$backtrace[1]['function']."' is not implemented for users idndexed by: '".$config->users_indexed_by."'",
				 NULL, NULL, NULL, "Check configuration of your data layer.");

		return $err;
	}
}

?>

<?php
/**
 *	Definitions of common classes
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: class_definitions.php,v 1.28 2008/08/13 11:07:58 kozlik Exp $
 *	@package    serweb
 */ 

/**
 *	@package    serweb
 */ 
class CREG_list_item {
	var $reg, $label;
	function CREG_list_item($reg, $label){
		$this->reg=$reg;
		$this->label=$label;
	}
}

/**
 *	@package    serweb
 */ 
class Capplet_params {
	var $name, $value;
	function Capplet_params($name, $value){
		$this->name=$name;
		$this->value=$value;
	}
}

/**
 *	Class representating tabs on html page
 *
 *	@package    serweb
 */
class Ctab{
	var $name, $page, $enabled;
	
	/**
	 *	Constructor
	 *
	 *	@param	bool	$enabled	Should be tab displayed?
	 *	@param	string	$name		Name of tab. If starting by '@' is translated by $lang_str array
	 *	@param	string	$page		Script which generate html page after click on this tab
	 */
	function Ctab($enabled, $name, $page){
		$this->name = $name;
		$this->page = $page;
		$this->enabled = $enabled;
	}
	
	/**
	 *	Return name of the tab
	 *	
	 *	If the name starting by "@" translate it by $lang_str array - internationalization
	 *	
	 *	@return	string
	 */
	function get_name(){
		return Lang::internationalize($this->name);
	}
	
	/**
	 *	Return script which generate content of this tab
	 *
	 *	@return	string	
	 */
	function get_page(){
		return $this->page;
	}
	
	/**
	 *	Is tab enabled?
	 *	
	 *	@return	bool
	 */
	function is_enabled(){
		return (bool)$this->enabled;
	}
	
	/**
	 *	Enable tab
	 */
	function enable(){
		$this->enabled = true;
	}

	/**
	 *	Disable tab
	 */
	function disable(){
		$this->enabled = false;
	}
}


/**
 *	@package    serweb
 */ 
class Ccall_fw{
	var $action, $param1, $param2, $label;
	function Ccall_fw($action, $param1, $param2, $label){
		$this->action = $action;
		$this->param1 = $param1;
		$this->param2 = $param2;
		$this->label  = $label;
	}
	
	/*
		find object with $action, $param1, $param2 in $arr and return its label
	*/
	function get_label($arr, $action, $param1, $param2){
		if(is_array($arr)){
			foreach($arr as $row){
				if ($row->action == $action and
					$row->param1 == $param1 and
					$row->param2 == $param2)
					return $row->label;
			}
		}
		return $action.": ".$param1." ".$param2;
	}

	/*
		find object with $action, $param1, $param2 in $arr and return its key
	*/
	function get_key($arr, $action, $param1, $param2){
		if(is_array($arr)){
			foreach($arr as $key => $row){
				if ($row->action == $action and
					$row->param1 == $param1 and
					$row->param2 == $param2)
					return $key;
			}
		}
		return null;
	}

}

/**
 *	@package    serweb
 */ 
class Cconfig{
} 
 

/**
 *	@package    serweb
 */ 
class SerwebUser {
	var $classname = "SerwebUser";
	var $persistent_slots = array('uid', 'did', 'username', 'realm');

	var $uid;
	var $username;
	var $realm;
	var $did = null;
	var $domainname = null;
	var $uri = null;

	function &instance($uid, $username, $did, $realm){
		$obj = new SerwebUser();
		$obj->uid = $uid;
		$obj->username = $username;
		$obj->did = $did;
		$obj->realm = $realm;

		return $obj;
	}

	function &instance_by_refs(&$uid, &$username, &$did, &$realm){
		$obj = new SerwebUser();
		$obj->uid = &$uid;
		$obj->username = &$username;
		$obj->did = &$did;
		$obj->realm = &$realm;

		return $obj;
	}

	function &recreate_from_get_param($val){

		$val.=":"; //add stop mark to input string
		$parts = array();

		for ($i=0, $j=0; $i<strlen($val); $i++){
			if ($val[$i] != ":") continue;
			
			//skip quoted ":"
			if (isset($val[$i+1]) and $val[$i+1] == "'" and 
			    isset($val[$i-1]) and $val[$i-1] == "'"){
				$i++;
				continue;
			}
			
			// at $i position is single ":"
			$parts[] = substr($val, $j, $i-$j);
			$j = $i+1;
			
		}

		foreach ($parts as $k=>$v) $parts[$k] = str_replace("':'", ":", $v);

		if ($parts[0]=="") $parts[0] = null;	//if UID is empty, set it to null
		if ($parts[1]=="") $parts[1] = null;	//if DID is empty, set it to null

		$obj = &SerwebUser::instance($parts[0], $parts[2], $parts[1], $parts[3]);
		return $obj;
	}
	
	function get_uid(){
		return $this->uid;
	}

	function get_username(){
		return $this->username;
	}

	function get_domainname(){

		if (!is_null($this->domainname)) return $this->domainname;

		if (false === $did = $this->get_did()) return false;

		$dh = &Domains::singleton();
		if (false === $domainname = $dh->get_domain_name($did)) return false;
		$this->domainname = $domainname;

		return $this->domainname;
	}

	function get_realm(){
		return $this->realm;
	}

	function get_uri(){
	
		if (!is_null($this->uri)) return $this->uri->to_string();

		$uh = &URIs::singleton($this->uid);
		if (false === $uri = $uh->get_URI()) return false;
		if (is_null($uri)) return "";
		$this->uri = $uri;

		return $this->uri->to_string();
	}

	function get_did(){
		global $data_auth;
		
		if (!is_null($this->did)) return $this->did;

		$data_auth->add_method('get_did_by_realm');
		
		$opt = array('check_disabled_flag' => false);
		if (false === $did = $data_auth->get_did_by_realm($this->realm, $opt)) return false;

		$this->did = $did;

		return $this->did;
	}

	function to_get_param($param = null){
	
		if (is_null($param)){
			if (isset($GLOBALS['controler']) and 
                is_a($GLOBALS['controler'], 'page_conroler')){
				$param = $GLOBALS['controler']->ch_user_param_name();
			}
			else{
				$param = "user";
			}
		}

		/* single quote all ":" */
		$uid = str_replace(":", "':'", $this->uid);
		$did = str_replace(":", "':'", $this->did);
		$realm = str_replace(":", "':'", $this->realm);
		$username = str_replace(":", "':'", $this->username);
	
		return $param."=".RawURLencode($uid.":".$did.":".$username.":".$realm);
	}

	
	function to_smarty(){
		return array('uname' => $this->username,
		             'realm' => $this->realm);
	}
}

/**
 *	@package    serweb
 */ 
class ErrorHandler{
	var $errors = array();

    /**
     * Return a reference to a ErrorHandler instance, only creating a new instance 
	 * if no ErrorHandler instance currently exists.
     *
     * You should use this if there are multiple places you might create a
     * ErrorHandler, you don't want to create multiple instances, and you don't 
	 * want to check for the existance of one each time. The singleton pattern 
	 * does all the checking work for you.
     *
     * <b>You MUST call this method with the $var = &ErrorHandler::singleton() 
	 * syntax. Without the ampersand (&) in front of the method name, you will 
	 * not get a reference, you will get a copy.</b>
     *
     * @access public
     */

    function &singleton() {
        static $instance = null;

		if (is_null($instance)) {
			$instance = new ErrorHandler();
		}
        return $instance;
    }

    /**
     *	Add an error message to the array of error messages
     *
     *	This method may be called staticaly e.g.: ErrorHandler::add_error($message);
     *	or dynamicaly e.g. $e = &ErrorHandler::singleton(); $e->add_error($message);
     *
     *	@param	mixed	$message	string or array of strings
     *	@return	none
     */
     
	function add_error($message){
		
		if (isset($this) and is_a($this, 'ErrorHandler')) $in = &$this;
		else $in = &ErrorHandler::singleton();

		if (is_array($message)){
			$in->errors = array_merge($in->errors, $message);
		}
		else
			$in->errors[] = $message;
	}

	/**
	 *	get error message from PEAR_Error object and write it to $errors array and to error log
	 *
     *	This method may be called staticaly e.g.: ErrorHandler::log_errors($err_object);
     *	or dynamicaly e.g. $e = &ErrorHandler::singleton(); $e->log_errors($err_object);
     *
	 *	@param object $err_object PEAR_Error object
     *	@return	none
	 */

	function log_errors($err_object){
		
		if (isset($this) and is_a($this, 'ErrorHandler')) $in = &$this;
		else $in = &ErrorHandler::singleton();

		log_errors($err_object, $in->errors);
	}



    /**
     *	Set internal variable containing error messages to be a reference to given array
     *
     *	@param	array	$errors
     *	@return	none
     */
     
	function set_errors_ref(&$errors){
		$this->errors = &$errors;
	}	

    /**
     *	Return array of error messages (as reference))
     *
     *	@return	array
     */
     
	function &get_errors_array(){
		return $this->errors;
	}	
}

/**
 *	Class handling domains
 *
 *	@package    serweb
 */ 
class Domains{

	var $domains = null;
	var $domain_names = null;

    /**
     * Return a reference to a Domains instance, only creating a new instance 
     * if no Domains instance currently exists.
     *
     * You should use this if there are multiple places you might create a
     * Domains, you don't want to create multiple instances, and you don't 
     * want to check for the existance of one each time. The singleton pattern 
     * does all the checking work for you.
     *
     * <b>You MUST call this method with the $var = &Domains::singleton() 
     * syntax. Without the ampersand (&) in front of the method name, you will 
     * not get a reference, you will get a copy.</b>
     *
     * @access public
     */

    function &singleton() {
        $obj =  &StaticVarHandler::getvar("Domains", 0, false);

        if (is_null($obj)) {
            $obj = new Domains();
        }

        return $obj;
    }

    /**
     *  Free memory ocupied by instance of Domains class
     *
     *  @access public
     *  @static
     */

    function free() {
        StaticVarHandler::getvar("Domains", 0, true);
    }

	/*
	 *	Load info about domains from DB
	 */
	function load_domains(){
		global $data, $config;
		
		if (!$config->multidomain){
			$this->domains = array();
			$this->domain_names = array();

			$this->domains[$config->domain] = array('did' => $config->default_did,
			                                        'name' => $config->domain,
													'disabled' => false,
													'canon' => true);
			$this->domain_names[$config->default_did][] = $config->domain;
			return true;		
		}
		
		$o = array('order_by' => 'canon',	//canonical domain names will be first
		           'order_desc' => true);

		$data->add_method('get_domain');
		if (false === $domains = $data->get_domain($o)) return false;
		
		$this->domains = array();
		$this->domain_names = array();

		foreach($domains as $k => $v){
			$this->domains[$v['name']] = &$domains[$k];
			$this->domain_names[$v['did']][] = $v['name'];
		}
	
		return true;
	}

	/**
	 *	Return array of domains indexed by domain names
	 *
	 *	@return	array 				array of domains or FALSE on error
	 */
	function &get_domains(){

		if (is_null($this->domains) and false === $this->load_domains()) 
			return false;
		
		return $this->domains;
	}

	/**
	 *	Return name of domain with given did
	 *
	 *	If canonical name is set, is returned preferentially
	 *	On error this function return FALSE. If domain with given $did doesn't 
	 *	exist, NULL is returned
	 *
	 *	@param	string	$did	domain id
	 *	@return	string			domain name or FALSE on error
	 */
	function get_domain_name($did){

		if (is_null($this->domain_names) and false === $this->load_domains()) 
			return false;
	
		if (!isset($this->domain_names[$did][0])) return null;
	
		return $this->domain_names[$did][0];
	}
	
	/**
	 *	Return array of all names of domain with given did
	 *
	 *	On error this function return FALSE. If domain with given $did doesn't 
	 *	exist, NULL is returned
	 *
	 *	@param	string	$did	domain id
	 *	@return	array			array of domain names or FALSE on error
	 */
	function get_domain_names($did){

		if (is_null($this->domain_names) and false === $this->load_domains()) 
			return false;
	
		if (!isset($this->domain_names[$did])) return null;
	
		return $this->domain_names[$did];
	}
	
	/**
	 *	Return ID of domain with given domain name
	 *
	 *	On error this function return FALSE. If domain with given name doesn't 
	 *	exist, NULL is returned
	 *
	 *	@param	string	$domainname	domain name
	 *	@return	string				domain ID or FALSE on error
	 */
	function get_did($domainname){

		if (is_null($this->domain_names) and false === $this->load_domains()) 
			return false;

		if (!isset($this->domains[$domainname]['did'])) return null;
	
		return $this->domains[$domainname]['did'];
	}

	/**
	 *	Return array of all alocated domain IDs 
	 *	
	 *	@return	array	array of domain IDs or FALSE on error
	 */
	function get_all_dids(){

		if (is_null($this->domain_names) and false === $this->load_domains()) 
			return false;

		return array_keys($this->domain_names);
	}
	

	/**
	 *	Return array of pairs (ID, name)
	 *	
	 *	array is indexed by IDs
	 *	
	 *	@return	array	array or FALSE on error
	 */

	function get_id_name_pairs(){

		if (is_null($this->domain_names) and false === $this->load_domains()) 
			return false;

		$out = array();
	
		foreach($this->domain_names as $k => $v)
			$out[$k] = $v[0];
			
		return $out;
	}
	
	
	/**
	 *	Sort array of domains by single levels of domain name. 
	 *	
	 *	Sort by top-level (e.g. .org) then by 2nd level (e.g. iptel in 
	 *	'iptel.org') etc.
	 *	
	 *	Keys of array are preserved
	 *	
	 *	@param	array
	 *	@return	none
	 */
	function sort_domains(&$domains){
	
		uasort($domains, array('Domains', 'sort_cmp_funct'));
	}

	
	/**
	 *	Comparsion function for sort_domains()
	 *	
	 *	@access	private
	 */
	function sort_cmp_funct($a, $b){

		/*	separate the domain names to top-level and the rest
		 	top-level parts are in x_tail variable, the rests are in x_nose
		 */
		if (false === $dot = strrpos($a, ".")){ $a_nose=""; $a_tail=$a; }
		else {$a_nose=substr($a, 0, $dot); $a_tail=substr($a, $dot+1);}

		if (false === $dot = strrpos($b, ".")){ $b_nose=""; $b_tail=$b; }
		else {$b_nose=substr($b, 0, $dot); $b_tail=substr($b, $dot+1);}

		/* domain names are equal */
		if ($a_tail == $b_tail and $a_tail=='') return 0;

		/* top-levels are equal call this function recursively to the rests of domain names */
		if ($a_tail == $b_tail) return Domains::sort_cmp_funct($a_nose, $b_nose);

		/* compare the top levels */
		if ($a_tail < $b_tail) return -1;
		else return 1;
	}
	
	
	/**
	 *	Generate DID for new domain
	 *
	 *	@static
	 *	@param	string	domainname	new name of domain
	 *	@return	string				did or FALSE on error
	 */
	function generate_new_did($domainname){
		global $data, $config;

		$an = &$config->attr_names;
		$errors = array();

		/* get format of did to generate */
		$ga = &Global_attrs::singleton();
		if (false === $format = $ga->get_attribute($an['did_format'])) return false;


		switch ($format){
		/* numeric DID */
		case 1:
			$data->add_method('get_new_domain_id');
			if (false === $did = $data->get_new_domain_id(null, $errors)) {
				ErrorHandler::add_error($errors);
				return false;
			}
			break;
			
		/* UUID by rfc4122 */
		case 2:
			$did = rfc4122_uuid();

			/* check if did doesn't exists */
			$dh = &Domains::singleton();
			if (false === $dids = $dh->get_all_dids()) return false; 
			
			while (in_array($did, $dids, true)){
				$did = rfc4122_uuid();
			}
			break;

		/* DID as 'domainname' */
		case 0:
		default:  /* if format of UIDs is not set, assume the first choice */

			if (!$domainname) $domainname = "default_domain";	// if domain name is not provided
			$did = $domainname;
			
			/* check if did doesn't exists */
			$dh = &Domains::singleton();
			if (false === $dids = $dh->get_all_dids()) return false; 

			$i = 0;
			while (in_array($did, $dids, true)){
 				$did = $domainname."_".$i++;
			}
			break;
		}

		return $did;
	}
}

/**
 *	Class representating one sip uri
 *
 *	@package    serweb
 */
class URI{
	var $flags;
	var $uid;
	var $did;
	var $username;
	var $scheme = "sip";

	function URI($uid, $did, $username, $flags){
		$this->uid		= $uid;
		$this->did		= $did;
		$this->username	= $username;
		$this->flags	= $flags;
	}

	function is_canonical(){
		global $config;
		
		$f = &$config->data_sql->uri->flag_values;
		return (bool)($this->flags & $f['DB_CANON']);
	}

	function is_to(){
		global $config;
		
		$f = &$config->data_sql->uri->flag_values;
		return (bool)($this->flags & $f['DB_IS_TO']);
	}

	function is_from(){
		global $config;
		
		$f = &$config->data_sql->uri->flag_values;
		return (bool)($this->flags & $f['DB_IS_FROM']);
	}

	function is_disabled(){
		global $config;
		
		$f = &$config->data_sql->uri->flag_values;
		return (bool)($this->flags & $f['DB_DISABLED']);
	}

	
	/**
	 *	Return scheme of the uri
	 *	
	 *	@return	string
	 */
	function get_scheme(){
		return $this->scheme;
	}

	/**
	 *	Set scheme of the uri
	 */
	function set_scheme($val){
		$this->scheme = $val;
	}
	
	
	/**
	 *	Return uid of the uri
	 *	
	 *	@return	string
	 */
	function get_uid(){
		return $this->uid;
	}
	
	/**
	 *	Return username part of the uri
	 *	
	 *	@return	string
	 */
	function get_username(){
		return $this->username;
	}
	
	/**
	 *	Return did of the uri
	 *	
	 *	@return	string
	 */
	function get_did(){
		return $this->did;
	}
	
	/**
	 *	Return flags of the uri
	 *	
	 *	@return	int
	 */
	function get_flags(){
		return $this->flags;
	}
	
	/**
	 *	Return URI as associative array which can be used as smarty variable
	 *	
	 *	@return	array		sip uri or FALSE on error
	 */
	function to_smarty(){
		$dom = &Domains::singleton();
		if (false === $dn = $dom->get_domain_name($this->did)) return false;

		$out = array();
		$out['scheme']   = $this->get_scheme();
		$out['uid']      = $this->get_uid();
		$out['username'] = $this->get_username();
		$out['did']      = $this->get_did();
		$out['domain']   = $dn;
		$out['is_canon'] = $this->is_canonical();
		$out['is_to']    = $this->is_to();
		$out['is_from']  = $this->is_from();
		$out['disabled'] = $this->is_disabled();
		if (false === $out['as_string'] = $this->to_string()) return false;

		return $out;
	}

	/**
	 *	Return URI in form 'sip:username@domain'
	 *	
	 *	@return	string		sip uri or FALSE on error
	 */
	function to_string(){
		global $config;

		/* global tel uri */
		if (strtolower($this->scheme) == "tel" and $this->did == $config->global_tel_uri_did){
			return "tel:".$this->username;
		}

		$dom = &Domains::singleton();
		if (false === $dn = $dom->get_domain_name($this->did)) return false;

		/* tel uri with context */
		if (strtolower($this->scheme) == "tel"){
			return "tel:".$this->username.";phone-context=".$dn;
		}
		
		/* sip uri */
		return "sip:".$this->username."@".$dn;
	}
}


/**
 *	Class handling URIs of user
 *
 *	@package    serweb
 */ 
class URIs{
	var $uid;
	/** filter URI by username */
	var $f_username = null;
	/** filter URI by did */
	var $f_did = null;
	/** index of canonical URI */
	var $canon = null;
	/** array of URIs of user */
	var $URIs = null;

    /**
     *
     * @access private
     */
	function URIs($uid){
		$this->uid = $uid;
	}

    /**
     * Return a reference to a URIs instance, only creating a new instance 
	 * if no URIs instance currently exists.
     *
     * You should use this if there are multiple places you might create a
     * URIs, you don't want to create multiple instances, and you don't 
	 * want to check for the existance of one each time. The singleton pattern 
	 * does all the checking work for you.
     *
     * <b>You MUST call this method with the $var = &URIs::singleton($uid) 
	 * syntax. Without the ampersand (&) in front of the method name, you will 
	 * not get a reference, you will get a copy.</b>
     *
     * @access public
     * @static
     */

    function &singleton($uid) {
        $obj =  &StaticVarHandler::getvar("URIs", $uid, false);

        if (is_null($obj)) {
            $obj = new URIs($uid);
        }

        return $obj;
    }


    /**
     *  Free memory ocupied by instance of URIs class
     *
     *  @access public
     *  @static
     */

    function free($uid) {
        StaticVarHandler::getvar("URIs", $uid, true);
    }

	
    /**
     * Return a reference to a URIs instance, only creating a new instance 
	 * if no URIs instance currently exists.
     *
     * URIs instance will contain URI with given username and did
     *
     * You should use this if there are multiple places you might create a
     * URIs, you don't want to create multiple instances, and you don't 
	 * want to check for the existance of one each time. The singleton pattern 
	 * does all the checking work for you.
     *
     * <b>You MUST call this method with the $var = &URIs::singleton_2($username, $did) 
	 * syntax. Without the ampersand (&) in front of the method name, you will 
	 * not get a reference, you will get a copy.</b>
     *
     * @access public
     */

    function &singleton_2($username, $did) {
        static $instances = array();

		$username = strtolower($username);

		$key = $username."@".$did;

		if (!isset($instances[$key])) {
			$instances[$key] = new URIs(null);
			$instances[$key]->f_username = $username;
			$instances[$key]->f_did      = $did;
		}
        return $instances[$key];
    }
	
	
	/**
	 *	
	 *	
	 *	@access private
	 */
	function load_URIs(){
		global $data;
		
		$data->add_method('get_aliases');
		
		$opt = array();
		$opt['filter'] = array();
		
		if (!is_null($this->f_username)) $opt['filter']['username'] = $this->f_username;
		if (!is_null($this->f_did))      $opt['filter']['did']      = $this->f_did;
		
		if (false === $uris = $data->get_aliases($this->uid, $opt)) return false;
		$this->URIs = &$uris;

		return true;	
	}
	
	/**
	 *	Invalidate cached URI
	 *
	 *	When get_uri or get_uris is called next time, URI will be re-readed
	 *	from DB 
	 */
	function invalidate(){
		$this->URIs = null;
	}
	
	/**
	 *	Return array of URIs of user 
	 *	
	 *	@return	array	array of URIs or FALSE on error
	 */
	function get_URIs(){

		if (is_null($this->URIs) and false === $this->load_URIs()) 
			return false;

		return $this->URIs;
	}

	/**
	 *	Return URI of user 
	 *	
	 *	If canonical URI is set, is returned preferentially
	 *	On error this function return FALSE. If no URI exists
	 *	NULL is returned
	 *	
	 *	@return	array	URI of user or FALSE on error
	 */
	function get_URI(){

		if (is_null($this->URIs) and false === $this->load_URIs()) 
			return false;

		/* if array of URIs is empty */
		if (!count($this->URIs)) return null;

		/* if index of canonical URI is known, return it */
		if (!is_null($this->canon)) return $this->URIs[$this->canon];

		/* try found the canonical URI */
		foreach($this->URIs as $k=>$v){
			if ($v->is_canonical()) {$this->canon = $k; break;}
		}

		/* if canonical uri doesn't exists use first URI instead of it */
		if (is_null($this->canon)) $this->canon = 0;
		
		return $this->URIs[$this->canon];
	}
}

/**
 *	@package    serweb
 */ 
class Credential{
	var $uid;
	var $did;
	var $c_did;	// did from credentials table
	var $r_did; // did obtained from realm
	var $uname;
	var $realm;
	var $flags;
	var $password;
	var $ha1;
	var $ha1b;

	var $did_changed = false;
	var $uname_changed = false;
	var $realm_changed = false;
	var $password_changed = false;
	var $flags_changed = false;
	var $ha1_changed = false;

	function Credential($uid, $did, $uname, $realm, $password, $ha1, $ha1b, $flags){
		$this->uid      = $uid;
		$this->c_did    = $did;
		$this->uname    = $uname;
		$this->realm    = $realm;
		$this->password = $password;
		$this->ha1      = $ha1;
		$this->ha1b     = $ha1b;
		$this->flags    = $flags;
	}
	
	function get_uid(){
		return $this->uid;
	}

	function get_did(){
		global $config, $data;
		
		if (!is_null($this->did)) return $this->did;
		
		if ($config->auth['use_did']) {
			$this->did = $this->c_did;
		}
		else {
			$data->add_method('get_attr_by_val');

			/* get did */
			$o = array('name' =>  $config->attr_names['digest_realm'],
			           'value' => $this->realm);
			if (false === $attrs = $data->get_attr_by_val("domain", $o)) return false;

			if (!empty($attrs[0]['id'])) {
				$this->did = $attrs[0]['id'];
			}
		}

		return $this->did;
	}

	function get_uname(){
		return $this->uname;
	}

	function get_realm(){
		return $this->realm;
	}
	
	function get_password(){
		return $this->password;
	}

	function get_flags(){
		return $this->flags;
	}

	function get_ha1(){
		return $this->ha1;
	}

	function get_ha1b(){
		return $this->ha1b;
	}

	function is_for_ser(){
		global $config;
		return (bool)($this->flags & $config->data_sql->credentials->flag_values['DB_LOAD_SER']);
	}

	function is_for_serweb(){
		global $config;
		return (bool)($this->flags & $config->data_sql->credentials->flag_values['DB_FOR_SERWEB']);
	}

	function set_uname($str){
		if ($this->uname == $str) return;
		$this->uname_changed = true;
		$this->uname = $str;
	}

	function set_did($str){
		global $config;
		
		if ($config->auth['use_did']){
			if ($this->c_did != $str) $this->did_changed = true;
			$this->c_did = $this->did = $str;
		}
		else{
			$this->did = $str;
		}
	}

	function set_realm($str){
		if ($this->realm == $str) return;
		$this->realm_changed = true;
		$this->realm = $str;
		$this->recalc_ha1();
	}
	
	function set_password($str){
		if ($this->password == $str) return;
		$this->password_changed = true;
		$this->password = $str;
		$this->recalc_ha1();
	}

	function set_for_ser(){
		global $config;
		if ($this->is_for_ser()) return;
		$this->flags_changed = true;
		$this->flags = ($this->flags | $config->data_sql->credentials->flag_values['DB_LOAD_SER']);
	}

	function set_for_serweb(){
		global $config;
		if ($this->is_for_serweb()) return;
		$this->flags_changed = true;
		$this->flags = ($this->flags | $config->data_sql->credentials->flag_values['DB_FOR_SERWEB']);
	}
	
	function reset_for_ser(){
		global $config;
		if (!$this->is_for_ser()) return;
		$this->flags_changed = true;
		$this->flags = ($this->flags & ~$config->data_sql->credentials->flag_values['DB_LOAD_SER']);
	}

	function reset_for_serweb(){
		global $config;
		if (!$this->is_for_serweb()) return;
		$this->flags_changed = true;
		$this->flags = ($this->flags & ~$config->data_sql->credentials->flag_values['DB_FOR_SERWEB']);
	}

	function did_changed(){
		return $this->did_changed;
	}

	function uname_changed(){
		return $this->uname_changed;
	}

	function realm_changed(){
		return $this->realm_changed;
	}

	function password_changed(){
		return $this->password_changed;
	}

	function ha1_changed(){
		return $this->ha1_changed;
	}

	function flags_changed(){
		return $this->flags_changed;
	}

	function recalc_ha1(){
		$this->ha1  = md5($this->uname.":".$this->realm.":".$this->password);
		$this->ha1b = md5($this->uname."@".$this->realm.":".$this->realm.":".$this->password);
		$this->ha1_changed = true;
	}
	
	function to_smarty(){
		global $config;
		$f = &$config->data_sql->credentials->flag_values;

		$dh = &Domains::singleton();
		if (false === $domainname = $dh->get_domain_name($this->get_did())) return false;

		return array("uid"        => $this->uid,
		             "uname"      => $this->uname,
		             "did"        => $this->get_did(),
		             "domainname" => $domainname,
					 "realm"      => $this->realm,
					 "password"   => $this->password,
		             "ha1"        => $this->ha1,
		             "ha1b"       => $this->ha1b,
					 "for_ser"    => ($this->flags & $f['DB_LOAD_SER']),
					 "for_serweb" => ($this->flags & $f['DB_FOR_SERWEB']));
	}
}

/**
 *	OO extension for IPC semaphores
 *
 *	@package    serweb
 */
class Shm_Semaphore{
	var $max_acquire;
	var $perm;
	var $sem_id;
	
	/**
	 *	Constructor
	 *
	 *	@param	string	$path_name		
	 *	@param	string	$proj			project identifier - one character
	 *	@param	int		$max_acquire	The number of processes that can acquire the semaphore simultaneously
	 *	@param	int		$perm			permission bits 
	 */
    function Shm_Semaphore($path_name, $proj, $max_acquire = 1, $perm=0666){
        $key = ftok($path_name, $proj);
        $this->max_acquire = $max_acquire;
        $this->perm = $perm;
        $this->sem_id = sem_get($key, $this->max_acquire, $this->perm, true);
    }

	/**
	 *	blocks (if necessary) until the semaphore can be acquired
	 *
	 *	@return bool 	Returns TRUE on success or FALSE on failure.
	 */
    function acquire(){
        if (!sem_acquire($this->sem_id)) {
			ErrorHandler::log_errors(PEAR::raiseError("cannot acquire semaphore"));
            return false;
        }
        return true;
    }

	/**
	 *	releases the semaphore if it is currently acquired by the calling process
	 *
	 *	@return bool 	Returns TRUE on success or FALSE on failure.
	 */
    function release(){
		if (!sem_release($this->sem_id)) {
			ErrorHandler::log_errors(PEAR::raiseError("cannot release semaphore"));
			return false;
		}
		return true;
    }
}

/**
 *	@package    serweb
 */ 
class Filter {
	var $name;
	var $value="";
	var $op="like";
	var $asterisks=true;
	var $case_sensitive = false;

	function Filter($name, $value=null, $op="like", $asterisks=true, $case_sensitive=false){
		$this->name = $name;
		$this->value = $value;
		$this->op = $op;
		$this->asterisks = $asterisks;
		$this->case_sensitive = $case_sensitive;
	}
	
	function to_sql($var=null, $int=false){

		if (is_null($var)) $var = $this->name;
		
		
		if ($this->op == "is_null")		return $var." is null";

		$val = $this->value;
		
		if ($this->op == "like"){
		    /* escape '%' and '_' characters - these are not wildcards */
			$val = str_replace('%', '\%', $val);
			$val = str_replace('_', '\_', $val);
			
			/* replace '*' and '?' with their wildcard equivalent  */
			$val = str_replace('*', '%', $val);
			$val = str_replace('?', '_', $val);
			
			if ($this->asterisks) $val = "%".$val."%";
		}
		
		
		if ($int)	return $var." ".$this->op." ".(int)$val;
		else {
		    if ($this->case_sensitive){
        		return $var." ".$this->op." BINARY '".addslashes($val)."'";
            }
            else{
        		return $var." ".$this->op." '".addslashes($val)."'";
            } 
        }
	
	}

	function to_sql_bool($var=null){

		if (is_null($var)) $var = $this->name;
		
		if ($this->op == "is_null")		return $var." is null";

		$val = $this->value;
		
		if ($val){
			return "(".$var.")";
		}
		else{
			return "!(".$var.")";
		}
	
	}
}

/**
 *  Helper class used to store static class variables
 */
class StaticVarHandler{

    /**
     *  Get or clear the class variable
     *
     *  @param  string $class   name of class requesting the variable
     *  @param  string $key     name of variable or another index - for use by the class
     *  @param  bool   $free    if true, free memory ocupied by the variable
     *  @return mixed
     */
    function &getvar($class, $key, $free){
        static $vars;
        $dummy = null;
    
        if ($free) {
            if (isset($vars[$class][$key])) unset($vars[$class][$key]);
            return $dummy;
        }
        else{
            if (!isset($vars[$class][$key])) $vars[$class][$key]=null;
            return $vars[$class][$key];
        }
    }
}

?>

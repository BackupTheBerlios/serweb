<?php
/*
 * $Id: class_definitions.php,v 1.7 2006/01/20 14:43:57 kozlik Exp $
 */

class CREG_list_item {
	var $reg, $label;
	function CREG_list_item($reg, $label){
		$this->reg=$reg;
		$this->label=$label;
	}
}

class Capplet_params {
	var $name, $value;
	function Capplet_params($name, $value){
		$this->name=$name;
		$this->value=$value;
	}
}

class Ctab{
	var $name, $page, $enabled, $lang_str;
	function Ctab($enabled, $lang_str, $page){
		$this->lang_str = $lang_str;
		$this->name = $lang_str;
		$this->page = $page;
		$this->enabled = $enabled;
	}
}


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

class Cconfig{
} 
 
//class for storing authentication information
class Cserweb_auth{
	var $uuid, $uname, $domain;
	var $classname='Cserweb_auth';
	var $persistent_slots = array("uuid", "uname", "domain");

	function Cserweb_auth($uuid=null, $uname=null, $domain=null){
		$this->uuid   =	$uuid;
		$this->uname  =	$uname;
		$this->domain =	$domain;
	}
}

class SerwebUser extends Cserweb_auth{
	var $classname='SerwebUser';
	var $did = null;

	function SerwebUser($uid=null, $uname=null, $domain=null){
		$this->uuid   =	$uid;
		$this->uname  =	$uname;
		$this->domain =	$domain;
	}
	
	function get_uid(){
		return $this->uuid;
	}

	function get_username(){
		return $this->uname;
	}

	function get_realm(){
		return $this->domain;
	}

	function get_did(){
		if (!is_null($this->did)) return $this->did;

		/* find out domain id */
		$did = call_user_func_array(array('phplib_Auth', 'find_out_did'), 
		                            array($this->uname, $this->domain, $this->uuid, array()));

		if (false === $did) return false;
		$this->did = $did;

		return $this->did;
	}
}

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
 */
class Domains{

	var $domains = null;
	var $domain_names = null;

    function &singleton() {
        static $instance = null;

		if (is_null($instance)) $instance = new Domains();
        return $instance;
    }

	/*
	 *	Load info about domains from DB
	 */
	function load_domains(){
		global $data;
		
		$o = array('order_by' => 'canon',	//canonical domain names will be first
		           'order_desc' => true);

		$data->add_method('get_domain');
		if (false === $domains = $data->get_domain($o)) return false;
		
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
	
}

/**
 *	Class representating one sip uri
 */
class URI{
	var $flags;
	var $uid;
	var $did;
	var $username;

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
	 *	Return URI in form 'sip:username@domain'
	 *	
	 *	@return	string		sip uri or FALSE on error
	 */
	function to_string(){
		$dom = &Domains::singleton();
		
		if (false === $dn = $dom->get_domain_name($this->did)) return false;
		
		return "sip:".$this->username."@".$dn;
	}
}


/**
 *	Class handling URIs of user
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
     */

    function &singleton($uid) {
        static $instances = array();

		if (!isset($instances[$uid])) $instances[$uid] = new URIs($uid);
        return $instances[$uid];
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
	 *	@todo: select correct data layer class in XXL envirnment
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
?>

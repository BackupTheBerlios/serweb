<?php
/**
 *	Classes holding user, domain, global and uri attributes
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: udg_attrs.php,v 1.7 2007/10/11 14:13:30 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 

/**
 *	The ascendant of all classes holding the attributes
 *	
 *	@abstract
 *	@package    serweb
 *	@subpackage mod_attributes
 */
class Attrs_Common{

	var $attributes = null;

	function Attrs_Common(){
	}

	/**
	 *	
	 *	
	 *	@abstract
	 *	@access private
	 */
	function load_attrs(){
		die("called abstract function: ".__FILE__.":".__LINE__);
	}

	/**
	 *	
	 *	
	 *	@abstract
	 *	@access private
	 */
	function save_attr($name, $opt){
		die("called abstract function: ".__FILE__.":".__LINE__);
	}

	/**
	 *	
	 *	
	 *	@abstract
	 *	@access private
	 */
	function del_attr($name, $opt){
		die("called abstract function: ".__FILE__.":".__LINE__);
	}

	/**
	 *	Return array of types of attributes
	 *
	 *	Array is indexed by attribute name
	 *
	 *	@return	array 				array of types of attributes or FALSE on error
	 */
	function get_attributes(){
		global $data;

		if (is_null($this->attributes) and false === $this->load_attrs()) 
			return false;
		
		return $this->attributes;
	}

	/**
	 *	Return type of attribute $name
	 *
	 *	If type of attribute not exists
	 *
	 *	@param	string	$name		name of attribute
	 *	@return	object 				array of types of attributes or FALSE on error
	 */
	function get_attribute($name){
		global $data;
		
		if (is_null($this->attributes) and false === $this->load_attrs()) 
			return false;
		
		if (!isset($this->attributes[$name])) return null;
		
		return $this->attributes[$name];
	}

	function set_attribute($name, $value){

		/* get attributes in order to know if attribute shuld be inserted or updated */
		if (false === $this->get_attributes()) return false;

		if (false === $this->save_attr($name, $value)) return false;

		$this->attributes[$name] = $value;
		
		/* call on_update method of attribute */
		$attr_types = &Attr_types::singleton();
		if (false === $att = &$attr_types->get_attr_type($name)) return false;
		if (is_object($att) and false === $att->on_update($value)) return false;

		return true;
	}

	function unset_attribute($name){

		$opt = array();

		if (false === $this->del_attr($name, $opt)) return false;

		unset($this->attributes[$name]);
		return true;
	}
}


/**
 *	Class providing access to global attributes
 *	
 *	@package    serweb
 *	@subpackage mod_attributes
 */
class Global_Attrs extends Attrs_Common {

    /**
     *
     * @access private
     */
	function Global_Attrs(){
		parent::Attrs_Common();
	}

    /**
     * Return a reference to a Global_Attrs instance, only creating a new instance 
	 * if no Global_Attrs instance currently exists.
     *
     * You should use this if there are multiple places you might create a
     * Global_Attrs, you don't want to create multiple instances, and you don't 
	 * want to check for the existance of one each time. The singleton pattern 
	 * does all the checking work for you.
     *
     * <b>You MUST call this method with the $var = &Global_Attrs::singleton() 
	 * syntax. Without the ampersand (&) in front of the method name, you will 
	 * not get a reference, you will get a copy.</b>
     *
     * @access public
     */

    function &singleton() {
        $obj =  &StaticVarHandler::getvar("Global_Attrs", 0, false);

        if (is_null($obj)) {
            $obj = new Global_Attrs();
        }

        return $obj;
    }

    /**
     *  Free memory ocupied by instance of Global_Attrs class
     *
     *  @access public
     *  @static
     */

    function free() {
        StaticVarHandler::getvar("Global_Attrs", 0, true);
    }

	/**
	 *	
	 *	
	 *	@access private
	 */
	function load_attrs(){
		global $data;
		
		$data->add_method('get_global_attrs');
		if (false === $at = $data->get_global_attrs(null)) return false;
		$this->attributes = &$at;

		return true;	
	}

	/**
	 *	
	 *	
	 *	@access private
	 */
	function save_attr($name, $value){
		global $data;
		
		$opt = array();

		if (isset($this->attributes[$name])) {
			$opt['old_value'] = $this->attributes[$name];
		}

		$data->add_method('update_global_attr');
		if (false === $data->update_global_attr($name, $value, $opt)) return false;

		return true;	
	}

	/**
	 *	
	 *	
	 *	@access private
	 */
	function del_attr($name, $opt){
		global $data;
		
		$data->add_method('del_global_attr');
		if (false === $data->del_global_attr($name, $opt)) return false;

		return true;	
	}

}

/**
 *	Class providing access to domain attributes
 *	
 *	@package    serweb
 *	@subpackage mod_attributes
 */
class Domain_Attrs extends Attrs_Common {

    /**
     *
     * @access private
     */
	function Domain_Attrs($did){
		parent::Attrs_Common();
		$this->did = $did;
	}

    /**
     * Return a reference to a Domain_Attrs instance, only creating a new instance 
	 * if no Domain_Attrs instance currently exists.
     *
     * You should use this if there are multiple places you might create a
     * Domain_Attrs, you don't want to create multiple instances, and you don't 
	 * want to check for the existance of one each time. The singleton pattern 
	 * does all the checking work for you.
     *
     * <b>You MUST call this method with the $var = &Domain_Attrs::singleton() 
	 * syntax. Without the ampersand (&) in front of the method name, you will 
	 * not get a reference, you will get a copy.</b>
     *
     * @access public
     */

    function &singleton($did) {
        $obj =  &StaticVarHandler::getvar("Domain_Attrs", $did, false);

        if (is_null($obj)) {
            $obj = new Domain_Attrs($did);
        }

        return $obj;
    }

    /**
     *  Free memory ocupied by instance of Domain_Attrs class
     *
     *  @access public
     *  @static
     */

    function free($did) {
        StaticVarHandler::getvar("Domain_Attrs", $did, true);
    }

	/**
	 *	
	 *	
	 *	@access private
	 */
	function load_attrs(){
		global $data;
		
		$data->add_method('get_domain_attrs');
		if (false === $at = $data->get_domain_attrs($this->did, null)) return false;
		$this->attributes = &$at;

		return true;	
	}

	/**
	 *	
	 *	
	 *	@access private
	 */
	function save_attr($name, $value){
		global $data;
		
		$opt = array();

		if (isset($this->attributes[$name])) {
			$opt['old_value'] = $this->attributes[$name];
		}

		$data->add_method('update_domain_attr');
		if (false === $data->update_domain_attr($this->did, $name, $value, $opt)) return false;

		return true;	
	}

	/**
	 *	
	 *	
	 *	@access private
	 */
	function del_attr($name, $opt){
		global $data;
		
		$data->add_method('del_domain_attr');
		if (false === $data->del_domain_attr($this->did, $name, $opt)) return false;

		return true;	
	}

}

/**
 *	Class providing access to user attributes
 *	
 *	@package    serweb
 *	@subpackage mod_attributes
 */
class User_Attrs extends Attrs_Common {

    /**
     *
     * @access private
     */
	function User_Attrs($uid){
		parent::Attrs_Common();
		$this->uid = $uid;
	}

    /**
     * Return a reference to a User_Attrs instance, only creating a new instance 
	 * if no User_Attrs instance currently exists.
     *
     * You should use this if there are multiple places you might create a
     * User_Attrs, you don't want to create multiple instances, and you don't 
	 * want to check for the existance of one each time. The singleton pattern 
	 * does all the checking work for you.
     *
     * <b>You MUST call this method with the $var = &User_Attrs::singleton($uid) 
	 * syntax. Without the ampersand (&) in front of the method name, you will 
	 * not get a reference, you will get a copy.</b>
     *
     * @access public
     */

    function &singleton($uid) {
        $obj =  &StaticVarHandler::getvar("User_Attrs", $uid, false);

        if (is_null($obj)) {
            $obj = new User_Attrs($uid);
        }

        return $obj;
    }

    /**
     *  Free memory ocupied by instance of User_Attrs class
     *
     *  @access public
     *  @static
     */

    function free($uid) {
        StaticVarHandler::getvar("User_Attrs", $uid, true);
    }

	/**
	 *	
	 *	
	 *	@access private
	 */
	function load_attrs(){
		global $data;
		
		$data->add_method('get_user_attrs');
		if (false === $at = $data->get_user_attrs($this->uid, null)) return false;
		$this->attributes = &$at;

		return true;	
	}

	/**
	 *	
	 *	
	 *	@access private
	 */
	function save_attr($name, $value){
		global $data;
		
		$opt = array();

		if (isset($this->attributes[$name])) {
			$opt['old_value'] = $this->attributes[$name];
		}

		$data->add_method('update_user_attr');
		if (false === $data->update_user_attr($this->uid, $name, $value, $opt)) return false;

		return true;	
	}

	/**
	 *	
	 *	
	 *	@access private
	 */
	function del_attr($name, $opt){
		global $data;
		
		$data->add_method('del_user_attr');
		if (false === $data->del_user_attr($this->uid, $name, $opt)) return false;

		return true;	
	}

}

/**
 *	Class providing access to uri attributes
 *	
 *	@package    serweb
 *	@subpackage mod_attributes
 */
class Uri_Attrs extends Attrs_Common {

    /**
     *
     * @access private
     */
	function Uri_Attrs($scheme, $username, $did){
		parent::Attrs_Common();
		$this->scheme = $scheme;
		$this->username = $username;
		$this->did = $did;
	}

    /**
     * Return a reference to a Uri_Attrs instance, only creating a new instance 
	 * if no Uri_Attrs instance currently exists.
     *
     * You should use this if there are multiple places you might create a
     * Uri_Attrs, you don't want to create multiple instances, and you don't 
	 * want to check for the existance of one each time. The singleton pattern 
	 * does all the checking work for you.
     *
     * <b>You MUST call this method with the $var = &Uri_Attrs::singleton($scheme, $username, $did) 
	 * syntax. Without the ampersand (&) in front of the method name, you will 
	 * not get a reference, you will get a copy.</b>
     *
     * @access public
     */

    function &singleton($scheme, $username, $did) {
		$key = $scheme.":".$username."@".$did;

        $obj =  &StaticVarHandler::getvar("Uri_Attrs", $key, false);

        if (is_null($obj)) {
            $obj = new Uri_Attrs($scheme, $username, $did);
        }

        return $obj;
    }

    /**
     *  Free memory ocupied by instance of Uri_Attrs class
     *
     *  @access public
     *  @static
     */

    function free($scheme, $username, $did) {
		$key = $scheme.":".$username."@".$did;

        StaticVarHandler::getvar("Uri_Attrs", $key, true);
    }

	/**
	 *	
	 *	
	 *	@access private
	 */
	function load_attrs(){
		global $data;
		
		$data->add_method('get_uri_attrs');
		if (false === $at = $data->get_uri_attrs($this->scheme, $this->username, $this->did, null)) return false;
		$this->attributes = &$at;

		return true;	
	}

	/**
	 *	
	 *	
	 *	@access private
	 */
	function save_attr($name, $value){
		global $data;
		
		$opt = array();

		if (isset($this->attributes[$name])) {
			$opt['old_value'] = $this->attributes[$name];
		}

		$data->add_method('update_uri_attr');
		if (false === $data->update_uri_attr($this->scheme, $this->username, $this->did, $name, $value, $opt)) return false;

		return true;	
	}

	/**
	 *	
	 *	
	 *	@access private
	 */
	function del_attr($name, $opt){
		global $data;
		
		$data->add_method('del_uri_attr');
		if (false === $data->del_uri_attr($this->scheme, $this->username, $this->did, $name, $opt)) return false;

		return true;	
	}

}

?>

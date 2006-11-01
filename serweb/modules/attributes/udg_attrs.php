<?php
/*
 * $Id: udg_attrs.php,v 1.4 2006/11/01 13:38:48 kozlik Exp $
 */

/**
 *	Abstract class
 */
class Attrs_Common{

	var $attributes = null;

	function Attrs_Common(){
	}

	/**
	 *	
	 *	
	 *	@access private
	 */
	function load_attrs(){
		die("called abstract function: ".__FILE__.":".__LINE__);
	}

	/**
	 *	
	 *	
	 *	@access private
	 */
	function save_attr($name, $opt){
		die("called abstract function: ".__FILE__.":".__LINE__);
	}

	/**
	 *	
	 *	
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
        static $instance = null;

		if (is_null($instance)) $instance = new Global_Attrs();
        return $instance;
    }

	/**
	 *	
	 *	
	 *	@access private
	 *	@todo: select correct data layer class in XXL envirnment
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
	 *	@todo: select correct data layer class in XXL envirnment
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
	 *	@todo: select correct data layer class in XXL envirnment
	 */
	function del_attr($name, $opt){
		global $data;
		
		$data->add_method('del_global_attr');
		if (false === $data->del_global_attr($name, $opt)) return false;

		return true;	
	}

}

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
        static $instances = array();

		if (!isset($instances[$did])) $instances[$did] = new Domain_Attrs($did);
        return $instances[$did];
    }

	/**
	 *	
	 *	
	 *	@access private
	 *	@todo: select correct data layer class in XXL envirnment
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
	 *	@todo: select correct data layer class in XXL envirnment
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
	 *	@todo: select correct data layer class in XXL envirnment
	 */
	function del_attr($name, $opt){
		global $data;
		
		$data->add_method('del_domain_attr');
		if (false === $data->del_domain_attr($this->did, $name, $opt)) return false;

		return true;	
	}

}

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
        static $instances = array();

		if (!isset($instances[$uid])) $instances[$uid] = new User_Attrs($uid);
        return $instances[$uid];
    }

	/**
	 *	
	 *	
	 *	@access private
	 *	@todo: select correct data layer class in XXL envirnment
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
	 *	@todo: select correct data layer class in XXL envirnment
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
	 *	@todo: select correct data layer class in XXL envirnment
	 */
	function del_attr($name, $opt){
		global $data;
		
		$data->add_method('del_user_attr');
		if (false === $data->del_user_attr($this->uid, $name, $opt)) return false;

		return true;	
	}

}

class Uri_Attrs extends Attrs_Common {

    /**
     *
     * @access private
     */
	function Uri_Attrs($username, $did){
		parent::Attrs_Common();
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
     * <b>You MUST call this method with the $var = &Uri_Attrs::singleton($username, $did) 
	 * syntax. Without the ampersand (&) in front of the method name, you will 
	 * not get a reference, you will get a copy.</b>
     *
     * @access public
     */

    function &singleton($username, $did) {
        static $instances = array();

		$key = $username."@".$did;

		if (!isset($instances[$key])) $instances[$key] = new Uri_Attrs($username, $did);
        return $instances[$key];
    }

	/**
	 *	
	 *	
	 *	@access private
	 *	@todo: select correct data layer class in XXL envirnment
	 */
	function load_attrs(){
		global $data;
		
		$data->add_method('get_uri_attrs');
		if (false === $at = $data->get_uri_attrs($this->username, $this->did, null)) return false;
		$this->attributes = &$at;

		return true;	
	}

	/**
	 *	
	 *	
	 *	@access private
	 *	@todo: select correct data layer class in XXL envirnment
	 */
	function save_attr($name, $value){
		global $data;
		
		$opt = array();

		if (isset($this->attributes[$name])) {
			$opt['old_value'] = $this->attributes[$name];
		}

		$data->add_method('update_uri_attr');
		if (false === $data->update_uri_attr($this->username, $this->did, $name, $value, $opt)) return false;

		return true;	
	}

	/**
	 *	
	 *	
	 *	@access private
	 *	@todo: select correct data layer class in XXL envirnment
	 */
	function del_attr($name, $opt){
		global $data;
		
		$data->add_method('del_uri_attr');
		if (false === $data->del_uri_attr($this->username, $this->did, $name, $opt)) return false;

		return true;	
	}

}

?>

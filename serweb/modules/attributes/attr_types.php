<?php
/*
 * $Id: attr_types.php,v 1.7 2006/04/14 16:24:02 kozlik Exp $
 */

/**
 *	Class representing type of one attribute
 */
class Attr_type{
	var $name;
	var $raw_type;
	var $rich_type;
	var $type_spec;
	var $description;
	var $default_flags;
	var $flags;
	var $priority;
	var $opt = array();
	var $order;


	function &factory($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $order){
		$class = "Attr_type_".$rich_type;
		$classfile = dirname(__FILE__)."/attr_type_".$rich_type.".php";

        if (file_exists($classfile))
			include_once $classfile;

		if (class_exists($class)){
			$obj = new $class($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $order);
			return $obj;
		}

		sw_log("Unknown type '".$rich_type."' of attribute '".$name."'", PEAR_LOG_WARNING);	

		$obj = new Attr_type($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $order);
		return $obj;
	}

	/**
	 *	@access private
	 */
	function Attr_type($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $order){
		$this->name 			= $name;
		$this->raw_type			= $raw_type;
		$this->rich_type		= $rich_type;
		$this->type_spec		= $type_spec;
		$this->description		= $desc;
		$this->default_flags	= $def_flags;
		$this->flags			= $flags;
		$this->priority			= $priority;
		$this->order			= $order;
	}

	/**
	 *	Get raw type for given rich type
	 *	
	 *	@static
	 *	@param	string	$rich_type
	 *	@return	int					raw type or FALSE on error
	 */
	function get_raw_for_rich($rich_type){
		$class = "Attr_type_".$rich_type;
		$classfile = dirname(__FILE__)."/attr_type_".$rich_type.".php";

		if (class_exists($class)){
			return call_user_func(array($class, "raw_type"));
		}

        if (file_exists($classfile))
			include_once $classfile;

		if (class_exists($class)){
			return call_user_func(array($class, "raw_type"));
		}
	
		sw_log("Unknown type '".$rich_type."'", PEAR_LOG_WARNING);	
		
		return false;
	}

	/**
	 *	Return name of APU for editing 'type_spec' of specified type
	 *	If empty string is returned, this type does not use 'type_spec'
	 *	
	 *	@static
	 *	@param	string	$type
	 *	@return	string			name of APU or FALSE on error
	 */
	function get_apu_edit($type){
		$class = "Attr_type_".$type;
		$classfile = dirname(__FILE__)."/attr_type_".$type.".php";

		if (class_exists($class)){
			if (is_callable(array($class, 'apu_edit')))
				return call_user_func(array($class, "apu_edit"));
			else
				return "";
		}

        if (file_exists($classfile))
			include_once $classfile;

		if (class_exists($class)){
			if (is_callable(array($class, 'apu_edit')))
				return call_user_func(array($class, "apu_edit"));
			else
				return "";
		}
	
		sw_log("Unknown type '".$type."'", PEAR_LOG_WARNING);	
		
		return false;
	}

	/**
	 *	@static
	 *	@abstract
	 */
	function raw_type(){
		return 0;
	}
	
	/**
	 *	Return name of APU for edit 'type_spec'
	 *	
	 *	@return	string
	 */
	function apu_edit(){
		return "";
	}

	/**
	 *	Return true if attribute is multivalue
	 *
	 *	@return bool	
	 *	@todo   implement	
	 */
	 
	function is_multivalue(){
		global $config;
		$f = &$config->data_sql->attr_types->flag_values;

		return ($this->flags & $f['DB_MULTIVALUE']) == $f['DB_MULTIVALUE'];
	}
	
	/**
	 *	Return true if attribute should be present on registration form
	 *
	 *	@return bool	
	 *	@todo   implement	
	 */
	 
	function fill_on_register(){
		global $config;
		$f = &$config->data_sql->attr_types->flag_values;

		return ($this->flags & $f['DB_FILL_ON_REG']) == $f['DB_FILL_ON_REG'];
	}
	
	function is_for_users(){
		global $config;
		$pr = $config->data_sql->attr_types->priority_values['USER'];
		return ($this->priority & $pr) == $pr;
	}
	
	function is_for_domains(){
		global $config;
		$pr = $config->data_sql->attr_types->priority_values['DOMAIN'];
		return ($this->priority & $pr) == $pr;
	}
	
	function is_for_globals(){
		global $config;
		$pr = $config->data_sql->attr_types->priority_values['GLOBAL'];
		return ($this->priority & $pr) == $pr;
	}

	function is_for_ser(){
		global $config;
		$f = $config->data_sql->user_attrs->flag_values['DB_LOAD_SER'];
		return ($this->default_flags & $f) == $f;
	}

	function is_for_serweb(){
		global $config;
		$f = $config->data_sql->user_attrs->flag_values['DB_FOR_SERWEB'];
		return ($this->default_flags & $f) == $f;
	}

	function get_description(){
		global $lang_str;
		
		if (substr($this->description, 0, 1) == '@' and 
		    isset($lang_str[substr($this->description, 1)])){
		
			return $lang_str[substr($this->description, 1)];
		}
		
		return $this->description;
	}
	
	function get_name(){
		return $this->name;
	}

	function get_order(){
		return $this->order;
	}

	function get_raw_description(){
		return $this->description;
	}
	
	function get_type(){
		return $this->rich_type;
	}
	
	function get_raw_type(){
		return $this->raw_type;
	}
	
	function get_type_spec(){
		return $this->type_spec;
	}
	
	function get_default_flags(){
		return $this->default_flags;
	}
	
	function get_flags(){
		return $this->flags;
	}
	
	function get_priority(){
		return $this->priority;
	}
	
	/**
	 *	Transfer attribute type to associative array which can be displayed by Smarty
	 *	
	 *	@return	array
	 */
	function to_table_row(){
		global $config;

		$f = &$config->data_sql->attr_types->flag_values;
		$p = &$config->data_sql->attr_types->priority_values;
		$df = &$config->data_sql->user_attrs->flag_values;
		
		$out = array("name" => $this->name,
		             "type" => $this->rich_type,
		             "order" => $this->order,
					 "description" => $this->description);

		foreach($f as $k => $v){
			$out['flags'][$k] = (bool)(($this->flags & $v) == $v);
		}
					 
		foreach($p as $k => $v){
			$out['priority'][$k] = (bool)(($this->priority & $v) == $v);
		}

		foreach($df as $k => $v){
			$out['default_flags'][$k] = (bool)(($this->default_flags & $v) == $v);
		}

		return $out;
	}

	/**
	 *	
	 */
	function check_value(&$value){
		return true;
	}

	/**
	 *	Method called after attribute update
	 */
	function on_update($value){
		return true;
	}
	
	/**
	 *	Function add form element to the given form object	
	 *	
	 *	Possible options:
	 *    optional	(bool)		default: false
	 *		attribute is optional - may contain empty value
	 *	
	 *    err_msg	(string)	default: null
	 *		error message displayed when value of attribute is wrong
	 *	
	 */
	function form_element(&$form, $value, $opt=array()){
		global $lang_str;
		
		$form->add_element(array("type"=>"hidden",
	                             "name"=>"_hidden_".$this->name,
		                         "value"=>$value));

	}

	function set_name($str){
		$this->name = $str;
	}

	function set_type($str){
		if (false === $raw = Attr_type::get_raw_for_rich($str)) return false;

		$this->rich_type = $str;
		$this->raw_type = $raw;
	}

	/**
	 *	@param	mixed	$p
	 */
	function set_type_spec($p){
		$this->type_spec = $p;
	}

	function set_description($str){
		$this->description = $str;
	}

	function set_order($str){
		$this->order = $str;
	}

	function set_for_users(){
		global $config;
		$pr = $config->data_sql->attr_types->priority_values['USER'];
		$this->priority |= $pr;
	}

	function reset_for_users(){
		global $config;
		$pr = $config->data_sql->attr_types->priority_values['USER'];
		$this->priority &= ~$pr;
	}

	function set_for_domains(){
		global $config;
		$pr = $config->data_sql->attr_types->priority_values['DOMAIN'];
		$this->priority |= $pr;
	}

	function reset_for_domains(){
		global $config;
		$pr = $config->data_sql->attr_types->priority_values['DOMAIN'];
		$this->priority &= ~$pr;
	}

	function set_for_globals(){
		global $config;
		$pr = $config->data_sql->attr_types->priority_values['GLOBAL'];
		$this->priority |= $pr;
	}

	function reset_for_globals(){
		global $config;
		$pr = $config->data_sql->attr_types->priority_values['GLOBAL'];
		$this->priority &= ~$pr;
	}

	function set_for_ser(){
		global $config;
		$f = $config->data_sql->user_attrs->flag_values['DB_LOAD_SER'];
		$this->default_flags |= $f;
	}

	function reset_for_ser(){
		global $config;
		$f = $config->data_sql->user_attrs->flag_values['DB_LOAD_SER'];
		$this->default_flags &= ~$f;
	}

	function set_for_serweb(){
		global $config;
		$f = $config->data_sql->user_attrs->flag_values['DB_FOR_SERWEB'];
		$this->default_flags |= $f;
	}

	function reset_for_serweb(){
		global $config;
		$f = $config->data_sql->user_attrs->flag_values['DB_FOR_SERWEB'];
		$this->default_flags &= ~$f;
	}

	function set_multivalue(){
		global $config;
		$f = &$config->data_sql->attr_types->flag_values['DB_MULTIVALUE'];
		$this->flags |= $f;
	}

	function reset_multivalue(){
		global $config;
		$f = &$config->data_sql->attr_types->flag_values['DB_MULTIVALUE'];
		$this->flags &= ~$f;
	}

	function set_registration(){
		global $config;
		$f = &$config->data_sql->attr_types->flag_values['DB_FILL_ON_REG'];
		$this->flags |= $f;
	}

	function reset_registration(){
		global $config;
		$f = &$config->data_sql->attr_types->flag_values['DB_FILL_ON_REG'];
		$this->flags &= ~$f;
	}

	/**
	 *	
	 */
	function set_opt($name, $value){
		$this->opt[$name] = $value;
	}

} 



class Attr_type_lists extends Attr_type{


	/**
	 *	Return name of APU for edit 'type_spec'
	 *	
	 *	@return	string
	 */
	function apu_edit(){
		return "apu_attr_lists";
	}

	/**
	 *	Convert $this->type_spec into array which can be passed to phplib form object.
	 *	
	 *	@return array				array of options for phplib form
	 *	@access private
	 */
	function get_options_for_form(){
		$items = $this->type_spec;
		
		if (!is_array($items)) $items=array();
		$opt=array();

		foreach($items as $i_value => $i_label){
			$opt[]=array("label" => $i_label, "value" => $i_value);
		}
		
		return $opt;
	}

	function check_value(&$value){
		if (!is_array($this->type_spec)) return true;
		
		//if not $value, return first of items
		if ($value==""){
			reset($this->type_spec);
			list($i_value, $i_label) = each($this->type_spec); 
			$value = $i_value;
			return true;
		}

		//find value in item values
		if (isset($this->type_spec[$value])) return true;
		
		//$value not found in item values, try find it in item labels
		foreach($this->type_spec as $i_value => $i_label) 
			if (strcasecmp($value, $i_label) == 0) {
				$value = $i_value;
				return true;
			}
		//$value not found
		return false;
	}
}




  
class Attr_types{
  
	var $attr_types = null;

    /**
     * Return a reference to a Attr_types instance, only creating a new instance 
	 * if no Attr_types instance currently exists.
     *
     * You should use this if there are multiple places you might create a
     * Attr_types, you don't want to create multiple instances, and you don't 
	 * want to check for the existance of one each time. The singleton pattern 
	 * does all the checking work for you.
     *
     * <b>You MUST call this method with the $var = &Attr_types::singleton() 
	 * syntax. Without the ampersand (&) in front of the method name, you will 
	 * not get a reference, you will get a copy.</b>
     *
     * @access public
     */

    function &singleton() {
        static $instance = null;

		if (is_null($instance)) $instance = new Attr_types();
        return $instance;
    }

	/**
	 *	Return array of all possible types of attribute
	 *	
	 *	@return array
	 */
	function get_all_types(){
		return array('boolean', 'email', 'int', 'lang', 'list', 
		             'radio', 'sip_adr', 'string', 'timezone');
	}

	/**
	 *	Return array of types of attributes
	 *
	 *	Array is indexed by attribute name
	 *
	 *	@return	array 				array of types of attributes or FALSE on error
	 */
	function &get_attr_types(){
		global $data;
		
		if (is_null($this->attr_types)){
			$data->add_method('get_attr_types');
			if (false === $at = $data->get_attr_types(null)) return false;
		
			$this->attr_types = &$at;
		}
		
		return $this->attr_types;
	}

	/**
	 *	Return type of attribute $name
	 *
	 *	If type of attribute not exists
	 *
	 *	@param	string	$name		name of attribute
	 *	@return	object 				array of types of attributes or FALSE on error
	 */
	function &get_attr_type($name){
		global $data;
		
		if (is_null($this->attr_types)){
			$data->add_method('get_attr_types');
			if (false === $at = $data->get_attr_types(null)) return false;
		
			$this->attr_types = &$at;
		}
		
		if (!isset($this->attr_types[$name])) {
			$ret = null;
			return $ret;
		}
		
		return $this->attr_types[$name];
	}
}
?>

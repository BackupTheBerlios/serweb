<?php
/*
 * $Id: attr_types.php,v 1.14 2006/12/20 16:36:44 kozlik Exp $
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
	var $access;
	var $order;
	var $group;
	var $error_msg = null;


	function &factory($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order){
		$class = "Attr_type_".$rich_type;
		$classfile = dirname(__FILE__)."/attr_type_".$rich_type.".php";

        if (file_exists($classfile))
			include_once $classfile;

		if (class_exists($class)){
			$obj = new $class($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order);
			return $obj;
		}

		sw_log("Unknown type '".$rich_type."' of attribute '".$name."'", PEAR_LOG_WARNING);	

		$obj = new Attr_type($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order);
		return $obj;
	}

	/**
	 *	@access private
	 */
	function Attr_type($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order){
		$this->name 			= $name;
		$this->raw_type			= $raw_type;
		$this->rich_type		= $rich_type;
		$this->type_spec		= $type_spec;
		$this->description		= $desc;
		$this->default_flags	= $def_flags;
		$this->flags			= $flags;
		$this->priority			= $priority;
		$this->access			= $access;
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
	
	/**
	 *	Return true if attribute has to been set (has to have any not empty value)
	 *
	 *	@return bool	
	 *	@todo   implement	
	 */
	 
	function is_required(){
		global $config;
		$f = &$config->data_sql->attr_types->flag_values;

		return ($this->flags & $f['DB_REQUIRED']) == $f['DB_REQUIRED'];
	}
	
	function is_for_URIs(){
		global $config;
		$pr = $config->data_sql->attr_types->priority_values['URI'];
		return ($this->priority & $pr) == $pr;
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

	function is_to_flag(){
		global $config;
		$f = $config->data_sql->user_attrs->flag_values['DB_IS_TO'];
		return ($this->default_flags & $f) == $f;
	}

	function is_from_flag(){
		global $config;
		$f = $config->data_sql->user_attrs->flag_values['DB_IS_FROM'];
		return ($this->default_flags & $f) == $f;
	}


	function internationalize_str($str){
		return Lang::internationalize($str);
	}

	function get_description(){
		$desc = $this->internationalize_str($this->description);
		$parts = explode("|", $desc, 2);
		return trim($parts[0]);
	}
	
	function get_long_description(){
		$desc = $this->internationalize_str($this->description);
		$parts = explode("|", $desc, 2);

		if (isset($parts[1])) return trim($parts[1]);
		
		return null;
	}
	
	function get_name(){
		return $this->name;
	}

	function get_order(){
		return $this->order;
	}

	function get_group(){
		return $this->group;
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
	
	function get_access(){
		return $this->access;
	}
	
	function get_user_access_to_change(){
		return !(($this->access & 0x01) == 0x01);
	}
	
	function get_user_access_to_read(){
		return !(($this->access & 0x02) == 0x02);
	}
	
	/**
	 *	Return options for form access field
	 */
	function get_access_options(){
		global $lang_str;
		return array(array("value" => 0, "label" => $lang_str['at_access_0']),
		             array("value" => 1, "label" => $lang_str['at_access_1']),
		             array("value" => 3, "label" => $lang_str['at_access_3']));
	}
	
	function get_priority(){
		return $this->priority;
	}
	
	function get_err_msg(){
		return $this->error_msg;
	}

	/**
	 *	format value to display it as string
	 *	method should be overridden in children classes
	 */
	function format_value($value){
		return $value;
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
		             "group" => $this->group,
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
	 *	
	 */
	function validation_js_before(){
		return "";
	}
	
	/**
	 *	
	 */
	function validation_js_after(){
		return "";
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

	function set_access($n){
		$this->access = $n;
	}

	function set_order($str){
		$this->order = $str;
	}

	function set_group($str){
		$this->group = $str;
	}

	function set_for_URIs(){
		global $config;
		$pr = $config->data_sql->attr_types->priority_values['URI'];
		$this->priority |= $pr;
	}

	function reset_for_URIs(){
		global $config;
		$pr = $config->data_sql->attr_types->priority_values['URI'];
		$this->priority &= ~$pr;
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

	function set_to_flag(){
		global $config;
		$f = $config->data_sql->user_attrs->flag_values['DB_IS_TO'];
		$this->default_flags |= $f;
	}

	function reset_to_flag(){
		global $config;
		$f = $config->data_sql->user_attrs->flag_values['DB_IS_TO'];
		$this->default_flags &= ~$f;
	}

	function set_from_flag(){
		global $config;
		$f = $config->data_sql->user_attrs->flag_values['DB_IS_FROM'];
		$this->default_flags |= $f;
	}

	function reset_from_flag(){
		global $config;
		$f = $config->data_sql->user_attrs->flag_values['DB_IS_FROM'];
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

	function set_required(){
		global $config;
		$f = &$config->data_sql->attr_types->flag_values['DB_REQUIRED'];
		$this->flags |= $f;
	}

	function reset_required(){
		global $config;
		$f = &$config->data_sql->attr_types->flag_values['DB_REQUIRED'];
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

	/**
	 *	format value to display it as string
	 */
	function format_value($value){

		if (!is_array($this->type_spec)) return $value;
		if (isset($this->type_spec[$value])) return $this->type_spec[$value];

		return $value;
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
	var $attr_groups = null;

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
		return array('boolean', 'email_adr', 'int', 'lang', 'list', 
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

	/**
	 *	Return array of attribute groups
	 *
	 *	@return	array 				array of attribute groups or FALSE on error
	 */
	function &get_attr_groups(){
		global $data, $config;
		
		if (is_null($this->attr_groups)){
			$data->add_method('get_attr_type_groups');
			if (false === $grps = $data->get_attr_type_groups(null)) return false;

			$grp_order = array();
			foreach ($grps as $v){
				if (isset($config->data_sql->attr_types->groups[$v]['order'])) $grp_order[$v] = $config->data_sql->attr_types->groups[$v]['order'];
				else $grp_order[$v] = 50;
			}
	
			asort($grp_order);
			$this->attr_groups = array_keys($grp_order);
		}
		
		return $this->attr_groups;
	}

	function get_attr_group_label($grp){
		global $config;

		if (isset($config->data_sql->attr_types->groups[$grp]['label'])) 
			return Lang::internationalize($config->data_sql->attr_types->groups[$grp]['label']);

		return $grp;
	}

}
?>

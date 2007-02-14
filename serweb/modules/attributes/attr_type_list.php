<?php
/**
 *	Attribute type list
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: attr_type_list.php,v 1.4 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 

/**
 *	Attribute type list
 * 
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 
class Attr_type_list extends Attr_type_lists{
	function Attr_type_list($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order){
		parent::Attr_type($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order);
	}

	function raw_type(){
		return 2;
	}

	function form_element(&$form, $value, $opt=array()){
		parent::form_element($form, $value, $opt);

		$form->add_element(array("type"=>"select",
	                             "name"=>$this->name,
								 "size"=>1,
    	                         "value"=>$value,
								 "options"=>$this->get_options_for_form()));
	}
}

?>

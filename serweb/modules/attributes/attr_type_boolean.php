<?php

class Attr_type_boolean extends Attr_type{
	function Attr_type_boolean($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order){
		parent::Attr_type($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order);
	}

	function raw_type(){
		return 0;
	}

	/**
	 *	format value to display it as string
	 */
	function format_value($value){
		global $lang_str;
		return $value ? $lang_str['yes'] : $lang_str['no'];
	}

	function check_value(&$value){
		if (!$value or strcasecmp($value, "no") == 0) $value='0';
		else $value='1';
		return true;
	}

	function form_element(&$form, $value, $opt=array()){
		parent::form_element($form, $value, $opt);

		$form->add_element(array("type"=>"checkbox",
	                             "name"=>$this->name,
		                         "value"=>"1",
								 "checked"=>$value));
	}
}

?>

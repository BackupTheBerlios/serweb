<?php

class Attr_type_timezone extends Attr_type{
	var $timezones = array();

	function Attr_type_timezone($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $order){
		parent::Attr_type($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $order);
	}

	function raw_type(){
		return 2;
	}

	function &get_timezones(){
		static $timezones = null;
		global $data;

		if (!is_null($timezones)) return $timezones;
		
		$errors = array();
		$data->add_method('get_time_zones');
		$tz = $data->get_time_zones($errors);
		if (count($errors)) { 
			ErrorHandler::add_error($errors); 
			$out=false;  //Only variable references should be returned
			return $out; 
		}
		
		$timezones = &$tz;
		return $timezones;
	}


	function check_value(&$value){
		return true;
	}

	function form_element(&$form, $value, $opt=array()){
		global $data;
		parent::form_element($form, $value, $opt);

		if (false === $timezones = $this->get_timezones()) return false;
		$options=array();
		foreach ($timezones as $v) $options[]=array("label"=>$v, "value"=>$v);
	                             
		$form->add_element(array("type"=>"select",
	                             "name"=>$this->name,
	                             "options"=>$options,
	                             "size"=>1,
	                             "value"=>$value));
	                             
	}
}

?>

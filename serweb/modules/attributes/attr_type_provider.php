<?php

/** experimental class probabyly not working now */
class Attr_type_provider extends Attr_type{
	var $items;

	function Attr_type_provider($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority){
		parent::Attr_type($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority);

		$this->items=Array();
	}

	/*
		get list of providers from db to varible "items"
	*/	
	function get_from_db(){
		global $config, $errors, $data;

		$data->add_method('get_providers');
		if (false === $providers = $data->get_providers($errors)) return false;
		$this->items = &$providers;
	}
	
	function get_items(){
		if (!$this->items) $this->get_from_db();
		return $this->items;
	}


	function check_value(&$value){
		$items=$this->get_items();
		
		if (!$items) return true;
		if (!is_array($items)) return true;
		
		//if not $value, return first of items
		if ($value==""){
			reset($items);
			$item=current($items);
			$value = $item->value;
			return true;
		}

		//find value in item values
		foreach($items as $item) if ($item->value==$value) return true;
		
		//$value not found in item values, try find it in item labels

		foreach($items as $item) if (strcasecmp($value, $item->label) == 0) {
			$value = $item->value;
			return true;
		}
		//$value not found
		return false;
	}

	function form_element(&$form, $value, $opt=array()){
		parent::form_element($form, $value, $opt);

		$items=$this->get_items();

		if (!is_array($items)) $items=array();
		$opt=array();

		foreach($items as $item){
			$opt[]=array("label" => $item->label, "value" => $item->value);
		}
		
		$form->add_element(array("type"=>"select",
	                             "name"=>$this->name,
								 "size"=>1,
    	                         "value"=>$value,
								 "options"=>$opt));
	}
}

?>

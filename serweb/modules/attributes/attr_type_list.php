<?php

class Attr_type_list extends Attr_type_lists{
	function Attr_type_list($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority){
		parent::Attr_type($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority);
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

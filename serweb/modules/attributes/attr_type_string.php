<?php

class Attr_type_string extends Attr_type{
	function Attr_type_string($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order){
		parent::Attr_type($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order);
	}

	function raw_type(){
		return 2;
	}

	function check_value(&$value){
		/* if empty value is not allowed */
		if ($this->is_required() and $value == ""){
			return false;
		}
		return true;
	}

	function form_element(&$form, $value, $opt=array()){
		parent::form_element($form, $value, $opt);

		global $lang_str;

		/* set default values for options */
		$opt_err_msg  = isset($opt["err_msg"]) ? $opt["err_msg"] : null;


		$form->add_element(array("type"=>"text",
	                             "name"=>$this->name,
								 "size"=>16,
								 "maxlength"=>255,
    	                         "value"=>$value,
	                             "minlength"=> $this->is_required() ? 1 : 0,
	                             "length_e"=>$opt_err_msg ? $opt_err_msg : ("'".$this->get_description()."' ".$lang_str['fe_empty_not_allowed'])));
	}
}

?>

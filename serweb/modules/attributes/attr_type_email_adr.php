<?php

class Attr_type_email_adr extends Attr_type{
	function Attr_type_email_adr($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $order){
		parent::Attr_type($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $order);
	}

	function raw_type(){
		return 2;
	}

	function check_value(&$value){
		
		$reg = &CReg::Singleton();
		
		if (ereg("(".$reg->email.")", $value, $regs)){
			$value=$regs[1];
			return true;
		}
		/* if empty value is allowed */
		else if (!$this->is_required() and $value == ""){
			return true;
		}
		else return false;
	}

	function form_element(&$form, $value, $opt=array()){
		parent::form_element($form, $value, $opt);

		global $lang_str;

		/* set default values for options */
		$opt_err_msg  = isset($opt["err_msg"]) ? $opt["err_msg"] : null;

		$reg = &CReg::Singleton();
		$form->add_element(array("type"=>"text",
	                             "name"=>$this->name,
								 "size"=>16,
								 "maxlength"=>255,
    	                         "value"=>$value,
	                             "valid_regex"=> $this->is_required() ? "^".$reg->email."$" :
								                                        "^(".$reg->email.")?$",
	                             "valid_e"=>$opt_err_msg ? $opt_err_msg : ("'".$this->get_description()."' ".$lang_str['fe_is_not_valid_email'])));
	}
}

?>

<?php
/**
 *	Attribute type int
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: attr_type_int.php,v 1.9 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 

/**
 *	Attribute type int
 * 
 *	@package    serweb
 *	@subpackage mod_attributes
 */ 
class Attr_type_int extends Attr_type{
	function Attr_type_int($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order){
		parent::Attr_type($name, $raw_type, $rich_type, $type_spec, $desc, $def_flags, $flags, $priority, $access, $order);
	}

	function raw_type(){
		return 0;
	}

	/**
	 *	Return name of APU for edit 'type_spec'
	 *	
	 *	@return	string
	 */
	function apu_edit(){
		return "apu_attr_int";
	}

	function check_value(&$value){
		global $lang_str;
		
		$this->error_msg = null;

		/* if empty value is allowed */
		if (!$this->is_required() and $value == ""){
			return true;
		}

		if (ereg("(-?[0-9]+)", $value, $regs)) {
			$value=(int)$regs[1];
		}
		else{
			return false;
		}
		
		$min = $max = null;
		$ts = $this->get_type_spec();
		if (isset($ts['min']) and is_numeric($ts['min'])) $min = $ts['min'];
		if (isset($ts['max']) and is_numeric($ts['max'])) $max = $ts['max'];

		if (!is_null($min) and !is_null($max)){
			if ($value<$min  or $value>$max){
				if (!empty($ts['err'])) $this->error_msg = sprintf($this->internationalize_str($ts['err']), $min, $max);
				else $this->error_msg = "'".$this->get_description()."' ".sprintf($lang_str['err_at_int_range'], $min, $max);
				
				return false;
			}
		}
		elseif (!is_null($min)){
			if ($value<$min){
				if (!empty($ts['err'])) $this->error_msg = sprintf($this->internationalize_str($ts['err']), $min);
				else $this->error_msg = "'".$this->get_description()."' ".sprintf($lang_str['err_at_int_range_min'], $min);

				return false;
			}
		}
		elseif (!is_null($max)){
			if ($value>$max){
				if (!empty($ts['err'])) $this->error_msg = sprintf($this->internationalize_str($ts['err']), $max);
				else $this->error_msg = "'".$this->get_description()."' ".sprintf($lang_str['err_at_int_range_max'], $max);

				return false;
			}
		}

		return true;		
	}

	/**
	 *	
	 */
	function validation_js_after(){
		global $lang_str;

		$out = "";

		$min = $max = null;
		$ts = $this->get_type_spec();
		if (isset($ts['min']) and is_numeric($ts['min'])) $min = $ts['min'];
		if (isset($ts['max']) and is_numeric($ts['max'])) $max = $ts['max'];

		if (!is_null($min) and !is_null($max)){
			if (!empty($ts['err'])) $error_msg = sprintf($this->internationalize_str($ts['err']), $min, $max);
			else $error_msg = "'".$this->get_description()."' ".sprintf($lang_str['err_at_int_range'], $min, $max);

			$out .= "
				var val = Number(f.".$this->name.".value);
				
				if (val < ".$min." || val > ".$max."){
					alert('".addslashes($error_msg)."');
					f.".$this->name.".focus();
					return(false);
				}
			";
		}
		elseif (!is_null($min)){
			if (!empty($ts['err'])) $error_msg = sprintf($this->internationalize_str($ts['err']), $min);
			else $error_msg = "'".$this->get_description()."' ".sprintf($lang_str['err_at_int_range_min'], $min);

			$out .= "
				var val = Number(f.".$this->name.".value);
				
				if (val < ".$min."){
					alert('".addslashes($error_msg)."');
					f.".$this->name.".focus();
					return(false);
				}
			";
		}
		elseif (!is_null($max)){
			if (!empty($ts['err'])) $error_msg = sprintf($this->internationalize_str($ts['err']), $max);
			else $error_msg = "'".$this->get_description()."' ".sprintf($lang_str['err_at_int_range_max'], $max);

			$out .= "
				var val = Number(f.".$this->name.".value);
				
				if (val > ".$max."){
					alert('".addslashes($error_msg)."');
					f.".$this->name.".focus();
					return(false);
				}
			";
		}


		return $out;
	}


	function form_element(&$form, $value, $opt=array()){
		parent::form_element($form, $value, $opt);

		global $lang_str;

		/* set default values for options */
		$opt_err_msg  = isset($opt["err_msg"]) ? $opt["err_msg"] : null;

		$form->add_element(array("type"=>"text",
	                             "name"=>$this->name,
								 "size"=>16,
								 "maxlength"=>16,
    	                         "value"=>$value,
	                             "valid_regex"=> $this->is_required() ? "^-?[0-9]+$" :
								                                        "^-?[0-9]*$",
	                             "valid_e"=>$opt_err_msg ? $opt_err_msg : ("'".$this->get_description()."' ".$lang_str['fe_is_not_number'])));
	}
}

?>

<?
/*
 * $Id: user_preferences.php,v 1.1 2004/02/24 08:53:08 kozlik Exp $
 */

class UP_List_Items{
	var $label, $value;
	function UP_List_Items($label, $value){
		$this->label=$label;
		$this->value=$value;
	}
} 
 
class User_Preferences {
	var $att_types;
	var $reg;

	function User_Preferences(){

		$this->att_types['boolean'] = 	"Boolean";
		$this->att_types['int'] = 		"Integer";
		$this->att_types['string'] = 	"String";
		$this->att_types['sip_adr'] = 	"SIP address";
		$this->att_types['list'] =	 	"List of values";
		
		$this->reg = new Creg;				// create regular expressions class
	
	}

	/* 
		return value formated for output 
	*/
	
	function format_value_for_output($value, $type, $type_spec){
		switch ($type){
		case 'boolean':
			if ($value) return "yes";
			else return "no";
			break;
		
		case 'list':
			$items=unserialize($type_spec);
			if (is_Array($items)){
				foreach($items as $item){
					if ($item->value==$value) return $item->label;
				}
			}
			return $value;
			break;
			
		default:
			return $value;
		
		}
	}
	
	
	/* 
		format inputed value to internal form 
		return false if $value has bad value
	*/

	function format_inputed_value(&$value, $type, $type_spec){
		switch ($type){
		case 'boolean':
			if (!$value or strcasecmp($value, "no") == 0) $value='0';
			else $value='1';
			return true;
			break;
		
		case 'list':
			$items=unserialize($type_spec);
			if (!$items) return true;
			if (!is_array($items)) return true;
			
			//if not $value, return first of items
			if (!$value){
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
			break;
		
		case 'int':
			if (ereg("([0-9]+)", $value, $regs)) {
				$value=$regs[1];
				return true;
			}
			else return false;
			break;
			
		case 'sip_adr':
			if (strlen($value)==0) return true;
			
			if (ereg("(".$this->reg->sip_address.")", $value, $regs)){
				$value=$regs[1];
				return true;
			}
			else return false;
			break;
			
		default:
			return true;
		
		}
	}
	
	/*
		add form element to form
	*/

	function form_element(&$form, $att_name, $value, $type, $type_spec){
		switch ($type){
		case 'boolean':
			$form->add_element(array("type"=>"checkbox",
		                             "name"=>$att_name,
			                         "value"=>"1",
									 "checked"=>$value));
			break;
		case 'list':
			$items=unserialize($type_spec);
			if (!is_array($items)) $items=array();
			$opt=array();

			foreach($items as $item){
				$opt[]=array("label" => $item->label, "value" => $item->value);
			}
			
			$form->add_element(array("type"=>"select",
		                             "name"=>$att_name,
									 "size"=>1,
	    	                         "value"=>$value,
									 "options"=>$opt,
									 "extrahtml"=>"style='width:120px;'"));
			break;
		case 'int':
			$form->add_element(array("type"=>"text",
		                             "name"=>$att_name,
									 "size"=>16,
									 "maxlength"=>16,
	    	                         "value"=>$value,
		                             "valid_regex"=>"^[0-9]+$",
		                             "valid_e"=>$att_name." is not valid number",
									 "extrahtml"=>"style='width:120px;'"));
			break;
		case 'sip_adr':
			$form->add_element(array("type"=>"text",
		                             "name"=>$att_name,
									 "size"=>16,
									 "maxlength"=>255,
	    	                         "value"=>$value,
		                             "valid_regex"=>"^".$this->reg->sip_address."$",
		                             "valid_e"=>$att_name." is not valid sip address",
									 "extrahtml"=>"onBlur='sip_address_completion(this)' style='width:120px;'"));
			break;
		case 'string':
			$form->add_element(array("type"=>"text",
		                             "name"=>$att_name,
									 "size"=>16,
									 "maxlength"=>255,
	    	                         "value"=>$value,
									 "extrahtml"=>"style='width:120px;'"));
			break;
		}
	}
}

?>
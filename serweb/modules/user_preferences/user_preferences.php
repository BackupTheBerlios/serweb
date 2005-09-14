<?
/*
 * $Id: user_preferences.php,v 1.2 2005/09/01 15:12:06 kozlik Exp $
 */

/*
	class UP_List_Items contains list of items of rich type "list"
*/ 
 
class UP_List_Items{
	var $label, $value;
	function UP_List_Items($label, $value){
		$this->label=$label;
		$this->value=$value;
	}
} 

/*
	class UP_att_types contains labels and raw_types to rich_type
	
	raw_types is int values:
	   1 - integer value
	   2 - string value
*/

class UP_att_types{
	var $label, $raw_type;
	function UP_att_types($label, $raw_type){
		$this->label=$label;
		$this->raw_type=$raw_type;
	}
}

/*
	class UP_providers contains list of providers form db table providers
*/
class UP_providers{
	var $items;
	
	function UP_providers(){
		global $data;
		$this->items=Array();
		
		$data->add_method('get_providers');
	}

	/*
		get list of providers from db to varible "items"
	*/	
	function get_from_db(){
		global $config, $errors, $data;

		if (false === $providers = $data->get_providers($errors)) return false;
		$this->items = &$providers;
	}
	
	function get_items(){
		if (!$this->items) $this->get_from_db();
		return $this->items;
	}
	
}
 
class User_Preferences {
	var $att_types;
	var $reg;
	var $providers;

	function User_Preferences(){

		$this->att_types['boolean'] = 	new UP_att_types("Boolean", 1);
		$this->att_types['int'] = 		new UP_att_types("Integer", 1);
		$this->att_types['string'] = 	new UP_att_types("String", 2);
		$this->att_types['sip_adr'] = 	new UP_att_types("SIP address", 2);
		$this->att_types['list'] =	 	new UP_att_types("List of values", 2);
		$this->att_types['radio'] =	 	new UP_att_types("List of values - radio", 2);
//		$this->att_types['provider'] = 	new UP_att_types("List of providers", 2);
		
		$this->reg = new Creg;				// create regular expressions class
		$this->providers = new UP_providers();
	
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
		case 'radio':
		case 'provider':
			if ($type=='list' or $type=='radio') $items=unserialize(is_string($type_spec)?$type_spec:"");
			if ($type=='provider') $items=$this->providers->get_items();

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
		case 'radio':
		case 'provider':
			if ($type=='list' or $type=='radio') $items=unserialize(is_string($type_spec)?$type_spec:"");
			if ($type=='provider') $items=$this->providers->get_items();
			
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

	function form_element(&$form, $att_name, $value, $type, $type_spec, $optional = false, $err_message = null){
		global $lang_str;
		
		$form->add_element(array("type"=>"hidden",
	                             "name"=>"_hidden_".$att_name,
		                         "value"=>$value));

		switch ($type){
		case 'boolean':
			$form->add_element(array("type"=>"checkbox",
		                             "name"=>$att_name,
			                         "value"=>"1",
									 "checked"=>$value));
			break;
		case 'list':
		case 'provider':
			if ($type=='list') $items=unserialize(is_string($type_spec)?$type_spec:"");
			if ($type=='provider') $items=$this->providers->get_items();

			if (!is_array($items)) $items=array();
			$opt=array();

			foreach($items as $item){
				$opt[]=array("label" => $item->label, "value" => $item->value);
			}
			
			$form->add_element(array("type"=>"select",
		                             "name"=>$att_name,
									 "size"=>1,
	    	                         "value"=>$value,
									 "options"=>$opt));
			break;
		case 'radio':
			$items=unserialize(is_string($type_spec)?$type_spec:"");

			if (!is_array($items)) $items=array();
			$opt=array();

			foreach($items as $item){
				$opt[]=array("label" => $item->label, "value" => $item->value);
			}
			
			$form->add_element(array("type"=>"radio",
		                             "name"=>$att_name,
									 "options"=>$opt,
	    	                         "value"=>$value));

			break;
		case 'int':
			$form->add_element(array("type"=>"text",
		                             "name"=>$att_name,
									 "size"=>16,
									 "maxlength"=>16,
	    	                         "value"=>$value,
		                             "valid_regex"=> $optional ? "^[0-9]*$" :
									                             "^[0-9]+$",
		                             "valid_e"=>$err_message ? $err_message : ($att_name." ".$lang_str['fe_is_not_number'])));
			break;
		case 'sip_adr':
			$form->add_element(array("type"=>"text",
		                             "name"=>$att_name,
									 "size"=>16,
									 "maxlength"=>255,
	    	                         "value"=>$value,
		                             "valid_regex"=> $optional ? "^(".$this->reg->sip_address.")?$" :
									                             "^".$this->reg->sip_address."$",
		                             "valid_e"=>$err_message ? $err_message : ($att_name." ".$lang_str['fe_is_not_sip_adr']),
									 "extrahtml"=>"onBlur='sip_address_completion(this)'"));
			break;
		case 'string':
			$form->add_element(array("type"=>"text",
		                             "name"=>$att_name,
									 "size"=>16,
									 "maxlength"=>255,
	    	                         "value"=>$value));
			break;
		}
	}
}

?>
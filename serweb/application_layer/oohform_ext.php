<?
/*
 * Extension for phplib object oriented html form
 *
 * $Id: oohform_ext.php,v 1.3 2006/05/23 09:36:21 kozlik Exp $
 */ 

class form_ext extends form{
	/* set to true if type of submit element is hidden -> javascript function for submit form is generated */
	var $hidden_submits = array();
	var $form_name = '';


	/* add submit element to from 
		$submit - associative array describing submit element
		
		Keys of $submit array:
			['type']  - type of submit element 'hidden', 'button', 'image'
			['text']  - text on button on alt on image
			['src']   - source of image
			['class'] - CSS class
	 */

	function add_submit($submit){
		$this->add_extra_submit("okey", $submit);
	}
	
	function add_extra_submit($name, $submit){
		if (! empty($submit['class'])) $class = " class = '".$submit['class']."'";
		else $class = '';

		switch ($submit['type']){
		case "image":
			$this->add_element(array("type"=>"submit",
			                             "name"=>$name,
			                             "src"=>$submit['src'],
										 "extrahtml"=>"alt='".$submit['text']."'".$class));
			break;
		case "button":
			$this->add_element(array("type"=>"submit",
			                             "name"=>$name."_x",
										 "value"=>$submit['text'],
										 "extrahtml"=>$class));
			break;
		case "hidden":
		default:
			$this->add_element(array("type"=>"hidden",
			                             "name"=>$name."_x",
			                             "value"=>'0',
										 "extrahtml"=>$class));
			$this->hidden_submits = $name."_x";
		}
	}
	
	
	function get_start($jvs_name="",$method="",$action="",$target="",$form_name="") {
		/* save form name */
		$this->form_name = $form_name;
		return parent::get_start($jvs_name, $method, $action, $target, $form_name);
	}
	
	
	function get_finish($after="",$before="") {
		$str = parent::get_finish($after, $before);
		
		/* if submit is hidden we must create javascript submit function which validate form */
		
		if (count($this->hidden_submits)){
			/* form_name must be set because it is part of name of the function */
			if ($this->form_name) {
				$str .= "<script language='javascript'>\n<!--\n";
				$str .= "function ".$this->form_name."_submit() {\n";
				$str .= "	".$this->form_name."_submit_extra('okey');\n";
				$str .= "}\n";
				
				$str .= "function ".$this->form_name."_submit_extra(name) {\n";
				/* if validator is set, call it */
				if ($this->jvs_name) {
					$str .= "  if (false != ".$this->jvs_name."_Validator(document.".$this->form_name.")) {\n";
					$str .= "    eval('document.".$this->form_name.".'+name+'_x.value=1'); \n";
					$str .= "    document.".$this->form_name.".submit(); \n";
					$str .= "  }\n";
				}
				/* otherwise only run submit */
				else {
					$str .= "    eval('document.".$this->form_name.".'+name+'_x.value=1'); \n";
					$str .= "  document.".$this->form_name.".submit(); \n";
				}
				$str .= "}\n";
				
				$str .= "//-->\n</script>";
			
			}
		}
		
		return $str;
	}

}


?>

<?
/*
 * Extension for phplib object oriented html form
 *
 * $Id: oohform_ext.php,v 1.1 2004/09/01 10:56:21 kozlik Exp $
 */ 

class form_ext extends form{
	/* set to true if type of submit element is hidden -> javascript function for submit form is generated */
	var $is_submit_hidden = false;
	var $form_name = '';


	/* add submit element to from 
		$submit - associative array describing submit element
		
		Keys of $submit array:
			['type'] - type of submit element 'hidden', 'button', 'image'
			['text'] - text on button on alt on image
			['src']  - source of image
	 */

	function add_submit($submit){
		switch ($submit['type']){
		case "image":
			$this->add_element(array("type"=>"submit",
			                             "name"=>"okey",
			                             "src"=>$submit['src'],
										 "extrahtml"=>"alt='".$submit['text']."'"));
			break;
		case "button":
			$this->add_element(array("type"=>"submit",
			                             "name"=>"okey_x",
										 "value"=>$submit['text']));
			break;
		case "hidden":
		default:
			$this->add_element(array("type"=>"hidden",
			                             "name"=>"okey_x",
			                             "value"=>'1'));
			$this->is_submit_hidden = true;
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
		if ($this->is_submit_hidden){
			/* form_name must be set because it is part of name of the function */
			if ($this->form_name) {
				$str .= "<script language='javascript'>\n<!--\n";
				$str .= "function ".$this->form_name."_submit() {\n";
				/* if validator is set, call it */
				if ($this->jvs_name) {
					$str .= "  if (false != ".$this->jvs_name."_Validator(document.".$this->form_name.")) {\n";
					$str .= "    document.".$this->form_name.".submit(); \n";
					$str .= "  }\n";
				}
				/* otherwise only run submit */
				else {
					$str .= "  document.".$this->form_name.".submit(); \n";
				}
				$str .= "}\n//-->\n</script>";
			
			}
		}
		
		return $str;
	}

}


?>
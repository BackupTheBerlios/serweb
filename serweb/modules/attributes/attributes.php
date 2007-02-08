<?php
/*
 * $Id: attributes.php,v 1.4 2007/02/08 15:24:16 kozlik Exp $
 */

class Attributes{

	/**
	 *	Get value of attribute
	 *	
	 *	This function search in order uri, user, domain and global tracks 
	 *	for attribute value and return value from the first track where find it.
	 *	
	 *	Alowed options:
	 *		- uid (string)	-	uid of user track
	 *		- did (string)	-	did of domain track
	 *		- uri (array)	-	identifies uri track. Have to have three 
	 *							components: scheme, username and did
	 *	
	 *	@param	string	$name	name of the attribute
	 *	@param	array	$opt	options
	 *	@return	mixed			value of attribute or FALSE on error
	 */
	function get_attribute($name, $opt){

		/* set default values for options */
		$opt_uid = isset($opt["uid"]) ? $opt["uid"] : null;
		$opt_did = isset($opt["did"]) ? $opt["did"] : null;
		$opt_uri = isset($opt["uri"]) ? $opt["uri"] : null;

		if (!is_null($opt_uri)){
			$attrs = &Uri_Attrs::singleton($opt_uri['scheme'], $opt_uri['username'], $opt_uri['did']);
			if (false === $attr = $attrs->get_attribute($name)) return false;
			
			if (!is_null($attr)) return $attr;
		}

		if (!is_null($opt_uid)){
			$attrs = &User_Attrs::singleton($opt_uid);
			if (false === $attr = $attrs->get_attribute($name)) return false;
			
			if (!is_null($attr)) return $attr;
		}

		if (!is_null($opt_did)){
			$attrs = &Domain_Attrs::singleton($opt_did);
			if (false === $attr = $attrs->get_attribute($name)) return false;
			
			if (!is_null($attr)) return $attr;
		}

		$attrs = &Global_Attrs::singleton();
		if (false === $attr = $attrs->get_attribute($name)) return false;
			
		if (!is_null($attr)) return $attr;
		
		/* attribute not found */
		return null;
	}


	/**
	 *	Create form elements for attributes
	 *	
	 *	Create form elements for attributes within form object and presets 
	 *	the default values of attributes
	 *	
	 *	Alowed options:
	 *		- uid (string)	-	uid of user track
	 *		- did (string)	-	did of domain track
	 *		- uri (array)	-	identifies uri track. Have to have three 
	 *							components: scheme, username and did
	 *		- get_values (bool) - 	if true, return current values of attributes 
	 *		                        as associative array in option 'attr_values'
	 *	
	 *	
	 *	@param	array	$attributes		list of attributes
	 *	@param	object	$f				form object
	 *	@param	string	$js_before		javascript called before form validation
	 *	@param	string	$js_after		javascript called after form validation
	 *	@param	array	$opt			options
	 *	@return	bool					TRUE on success or FALSE on error
	 */

	function attrs_to_form($attributes, &$f, &$js_before, &$js_after, &$opt){

		$a_opt = array();

		/* set values for options */
		if (isset($opt["uid"])) $a_opt["uid"] = $opt["uid"];
		if (isset($opt["did"])) $a_opt["did"] = $opt["did"];
		if (isset($opt["uri"])) $a_opt["uri"] = $opt["uri"];

		//get list of attributes
		$at_h = &Attr_types::singleton();
		if (false === $attr_types = &$at_h->get_attr_types()) return false;

		
		$attr_values = array();
		foreach($attributes as $attr){
			if (false === $val = Attributes::get_attribute($attr, $a_opt)) return false;
			$attr_values[$attr] = $val;
		}
		

		// add elements to form object
		foreach($attributes as $attr){
			$f_opt = array();

			$attr_types[$attr]->form_element($f, 
			                                 $attr_values[$attr],
			                                 $f_opt);

			$js_before .= $attr_types[$attr]->validation_js_before();
			$js_after  .= $attr_types[$attr]->validation_js_after();
		}
		
		if (!empty($opt['get_values'])) $opt['attr_values'] = $attr_values;
	
		return true;
	}
	

	/**
	 *	Validate new values of attributes received from html form
	 *	
	 *	Alowed options:
	 *		none for now
	 *	
	 *	@param	array	$attributes		list of attributes
	 *	@param	array	$opt			options
	 *	@param 	array	$errors			error messages if any errors has been found
	 *	@return	bool					TRUE on success or FALSE on error
	 */

	function validate_form_attrs($attributes, $opt, &$errors){
		global $lang_str;

		//get list of attributes
		$at_h = &Attr_types::singleton();
		if (false === $attr_types = &$at_h->get_attr_types()) return false;
		
		foreach($attributes as $att){
			if (!isset($_POST[$att])) $_POST[$att] = null;
			
			if (!$attr_types[$att]->check_value($_POST[$att])){
				if (!is_null($attr_types[$att]->get_err_msg())){
					$errors[]=$attr_types[$att]->get_err_msg(); 
				}
				else{
					$errors[]=$lang_str['fe_invalid_value_of_attribute']." ".$attr_types[$att]->get_description(); 
				}
				return false;
			}
		}
		
		return true;
	}


	/**
	 *	Convert new values of attributes received from html form to associative array
	 *	
	 *	Alowed options:
	 *		none for now
	 *	
	 *	@param	array	$attributes		list of attributes
	 *	@param	array	$opt			options
	 *	@return	array					array of attributes or FALSE on error
	 */

	function post_attrs_to_array($attributes, $opt){

		$attrs = array();
		foreach($attributes as $att)	{
			if (!isset($_POST[$att])) $attrs[$att] = null;
			else                      $attrs[$att] = $_POST[$att];
		}

		return $attrs;	
	}
	
	
	/**
	 *	Save bunch of attributes stored in associative array to DB
	 *	
	 *	Alowed options:
	 *		none for now
	 *	
	 *	@param	array	$attributes		asociative array of attributes
	 *	@param	object	$at_h			attribute handler - it could be uri, user, domain or global
	 *	@param	array	$opt			options
	 *	@return	array					array of attributes or FALSE on error
	 */

	function save_attrs($attributes, $at_h, $opt){
		
		if (!is_a($at_h, 'Attrs_Common')) 
			die(__FILE__.":".__LINE__."Wrong type of \$at_h parameter. It should be object of 'Attrs_Common' class.");
		
		foreach($attributes as $name => $val)	{
			/* store attr into DB */
			if (false === $at_h->set_attribute($name, $val)) {
				return false;
			}
		}	
		
		return true;
	}

}
?>

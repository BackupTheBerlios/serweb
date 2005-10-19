<?php
/**
 * Application unit domain preferences 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_domain_preferences.php,v 1.1 2005/10/19 10:32:14 kozlik Exp $
 * @package   serweb
 */ 

/**
 *	Application unit domain preferences
 *
 *
 *	This application unit is used for view and change domain preferences
 *	   
 *	Configuration:
 *	--------------
 *	
 *	'attributes'				(array)	default: $config->domain_preferences
 *	 array describing attributes
 *	 the keys in array are names of attributes. Each item in array should contain
 *	 associative array with these keys:
 *		'type'    - type of attribute, possible values are: 
 *		            'boolean', 'int', 'string', 'sip_adr', 'list', 'radio'
 *		            see class User_Preferences for details
 *		'desc'    - description of attribute. May contain reference into $lang_str array
 *		'options' - associative array of options for types: 'list' and 'radio'
 *		            keys contain values of options and values are description 
 *		            of options
 *								
 *	'error_messages'			(assoc) default: array()
 *	 keys are names of attributes, values are custom error messages
 *	 displayed when value of attribute is wrong
 *
 *	'validate_funct'			(string) default: ""
 *	 name of validate function
 *	 validate function must return true or false, first parametr is associative array of
 *	 attributes, second one is property 'error_messages' and third one is reference to errors 
 *	 array - function can add error message to it which is dispayed to user
 *	
 *		 example:
 *			function validate_form($values, $error_messages, &$errors){
 *				//validate only one attribute
 *				if (ereg('^[0-9]+$', $values['some_attribute'])) return true;
 *				else {
 *					$errors[]=$error_messages['some_attribute'];
 *					return false;
 *				}
 *			}
 *
 *			set_opt('validate_funct') = 'validate_form';
 *   
 *	'redirect_on_update'		(string) default: ''
 *	 name of script to which is browser redirected after succesfull update
 *	 if empty, browser isn't redirected
 *
 *	'msg_update'					default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
 *	 message which should be showed on attributes update - assoc array with keys 'short' and 'long'
 *								
 *	'form_name'					(string) default: ''
 *	 name of html form
 *	
 *	'form_submit'				(assoc)
 *	 assotiative array describe submit element of form. For details see description 
 *	 of method add_submit in class form_ext
 *	
 *	'smarty_form'				name of smarty variable - see below
 *	'smarty_action'				name of smarty variable - see below
 *	'smarty_attributes'			name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_attributes'] 	(attributes)	
 *	 associative array containing info about attributes
 *		keys: 
 *			'att_name' - name of attribute
 *			'att_desc' - human readable name of attribute
 *			'att_type' - type of attribute
 *			'att_spec' - for type 'radio' contain array of names of each radio button
 *			
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_action']			(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'was_updated' - when user submited form and data was succefully stored
 *	
 */

class apu_domain_preferences extends apu_base_class{
	var $smarty_action='default';
	/** javascript on form submit */
	var $js_on_subm = "";
	/** instance of class User_Preferences */
	var $pref_obj;
	/** array of current values of domain preferences */
	var $domain_preferences;
	
	/** 
	 *	return required data layer methods - static class 
	 *
	 *	@return array	array of required data layer methods
	 */
	function get_required_data_layer_methods(){
		return array('get_domain_preferences', 'update_domain_attribute');
	}

	/**
	 *	return array of strings - required javascript files 
	 *
	 *	@return array	array of required javascript files
	 */
	function get_required_javascript(){
		return array();
	}
	
	/**
	 *	constructor 
	 *	
	 *	initialize internal variables
	 */
	function apu_domain_preferences(){
		global $lang_str, $config;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['attributes'] =			isset($config->domain_preferences) ? $config->domain_preferences : array();
		$this->opt['error_messages'] = array();	
		$this->opt['validate_funct'] = null;	
		$this->opt['redirect_on_update']  = "";

		/* message on attributes update */
		$this->opt['msg_update']['short'] =	&$lang_str['msg_changes_saved_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_changes_saved_l'];
		
		/*** names of variables assigned to smarty ***/
		/* array containing names of attributes */
		$this->opt['smarty_attributes'] =	'attributes';
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';
		
		
	}

	/**
	 *	this metod is called always at begining - initialize variables
	 */
	function init(){
		parent::init();
		
		$this->pref_obj = new User_Preferences();
	}

	/**
	 *	Return description for attributes 
	 *
	 *	If is not set 'desc' param of attribute return $att_name
	 *	Otherwise if value of 'desc' is key into $lang_str array
	 *	return string from $lang_str. If $lang_str doesn't exists, 
	 *	return directly 'desc' param.
	 *
	 *	@param string	$att_name	name of attribute
	 *	@return string				description of attribute
	 */
	function get_description_of_attribute($att_name){
		if (isset($this->opt['attributes'][$att_name]['desc'])){
			$d = &$this->opt['attributes'][$att_name]['desc'];
			if (isset($lang_str[$d])){
				return $lang_str[$d];
			}
			else{
				return $d;
			}
		}
		return $att_name;
		
	}
		
	/** 
	 *	returned two dimensional array of attributes
	 *	it is ordered array which elements contain associative array, with keys:
	 *		'att_name'
	 *		'att_desc'
	 *		'att_type'
	 *		'att_spec' (for type 'radio' only)
	 *	
	 *	this method also create javascript on form submit for attributes which type is sip address
	 *
	 *	@return array
	 */
	function format_attributes_for_output(){
		global $sess, $lang_str;
	
		$out=array();
		foreach($this->opt['attributes'] as $k => $v){
			$name_of_attribute = &$k;
		
			if ($v['type'] == "sip_adr") 
					$this->js_on_subm .= "sip_address_completion(f.".$name_of_attribute.");";

		
			$out[$name_of_attribute]['att_desc'] = $this->get_description_of_attribute($k);
			$out[$name_of_attribute]['att_name'] = $name_of_attribute;
			$out[$name_of_attribute]['att_type'] = $v['type'];
			
			/* if type of attribute is radio, create list of options
			 * ass array of asociative arrays with entries 'label' and 'value'
			 */
			if ($v['type'] == "radio") {
				foreach($this->f->elements[$name_of_attribute]['ob']->options as $v){
					$out[$name_of_attribute]['att_spec'][] = $v;
				}
			}
		}
		return $out;
	}

	/**
	 *	Method perform action update
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_update(&$errors){
		global $data, $sess;

		//update all changed attributes
		foreach($this->opt['attributes'] as $k => $v){
			//if att value is changed
			if ($_POST[$k] != $_POST["_hidden_".$k]){
				if (false === $data->update_domain_attribute($this->controler->domain_id, 
																$k, 
																$_POST[$k], 
																array('insert' => !isset($this->domain_preferences[$k])),
																$errors)) 
					return false;
			}
		}

		if ($this->opt['redirect_on_update']){
			$this->controler->change_url_for_reload($this->opt['redirect_on_update']);
		}

		return array("m_dom_pref_updated=".RawURLEncode($this->opt['instance_id']));
	}
	
	/**
	 *	check _get and _post arrays and determine what we will do 
	 */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			$this->action=array('action'=>"update",
			                    'validate_form'=>true,
								'reload'=>true);
		}
		else $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
	}
	
	/**
	 *	create html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return null			FALSE on failure
	 */
	function create_html_form(&$errors){
		global $data, $lang_str;
		parent::create_html_form($errors);

		if (false === $this->domain_preferences = $data->get_domain_preferences($this->controler->domain_id, array(), $errors)) return false;

		// add elements to form object
		foreach($this->opt['attributes'] as $k => $v){
			if (!isset($this->domain_preferences[$k]) and $v['type'] == 'list'){
				$v['options'] = array_merge(array(null => $lang_str['choose_one']), $v['options']);
			}

			$this->pref_obj->form_element(
				$this->f, 
				$k, 
				isset($this->domain_preferences[$k]) ? $this->domain_preferences[$k] : null, 
				$v['type'], 
				isset($v['options']) ? $v['options'] : null,
				false,
				(isset($this->opt['error_messages'][$k])) ? ($this->opt['error_messages'][$k]) : null
			);
		}
		
	}

	/**
	 *	validate html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return bool			TRUE if given values of form are OK, FALSE otherwise
	 */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;

		//check values of attributes and format its
		foreach($this->opt['attributes'] as $k => $v){
			if (!$this->pref_obj->format_inputed_value($_POST[$k], 
														$v['type'], 
														null)){
				// value of attribute is wrong
				// if is set error message for this attribut, add it to $errors array
				// otherwise add to $errors array default message: $lang_str['fe_invalid_value_of_attribute']
				
				if (isset($this->opt['error_messages'][$k]))
					$errors[]=$this->opt['error_messages'][$k];
				else
					$errors[]=$lang_str['fe_invalid_value_of_attribute']." ".$this->get_description_of_attribute($k); 

				return false;
			}
		}
		

		//value of attributes seems to be ok, try to call user checking function yet
		if (!empty($this->opt['validate_funct']) and
			!call_user_func_array($this->opt['validate_funct'], array(&$_POST, $this->opt['error_messages'], &$errors))){
				//user checking function returned false -> value of attribute is wrong
				return false;
		}

		return true;
	}
	
	
	/**
	 *	add messages to given array 
	 *
	 *	@param array $msgs	array of messages
	 */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_dom_pref_updated']) and $_GET['m_dom_pref_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}
	}

	/**
	 *	assign variables to smarty 
	 */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign($this->opt['smarty_attributes'], $this->format_attributes_for_output());
	}
	
	/**
	 *	return info need to assign html form to smarty 
	 */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => '',
					 'before'      => '');
	}
}

?>

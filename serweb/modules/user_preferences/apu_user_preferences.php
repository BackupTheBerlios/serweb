<?
/*
 * $Id: apu_user_preferences.php,v 1.3 2005/09/02 14:00:25 kozlik Exp $
 */ 

/* Application unit user preferences */

/*
   This application unit is used for view and change values in table user preferences
   
   Configuration:
   --------------
   'attributes'					(array) with which attributes object should work - default with all atributes
   'exclude_attributes'			(array) with which attributes object shouldn't work - default empty
   'error_messages'				associative array - keys are names of attributes, values are custom error messages
                                displayed when value of attribute is wrong
   'validate_funct'				name of validate function
   								validate function must return true or false, first parametr is associative array of
								attributes, second one is property 'error_messages' and third one is reference to errors 
								array - function can add error message to it which is dispayed to user
								
								example:
									function validate_form($values, $error_messages, &$errors){
										//validate only one attribute
										if (ereg('^[0-9]+$', $values['some_attribute'])) return true;
										else {
											$errors[]=$error_messages['some_attribute'];
											return false;
										}
									}
	
									set_opt('validate_funct') = 'validate_form';
   
   'optionals'   				associative array - keys are names of attributes, values are booleans
                                can be used to make value of attribute optional
								(have efect only for some types of attributes (e.g. sip_adr, int)
								
   'att_description'			(array) description of attributes. Keys of this array are names of attributes.
   								Values are descriptions of attributes which are stored into keys 'att_desc' of
   								opt['smarty_action'] array
								
   'msg_update'					message which should be showed on attributes update - assoc array with keys 'short' and 'long'
   								default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
   'smarty_attributes'			name of smarty variable - see below
   'smarty_form'				name of smarty variable - see below
   'smarty_action'				name of smarty variable - see below
   'form_name'					name of html form

   'form_submit'				(assoc)
     assotiative array describe submit element of form. For details see description 
	 of method add_submit in class form_ext
   
   Exported smarty variables:
   --------------------------
   opt['smarty_attributes'] 	(attributes)	associative array containing info about attributes
   													keys: 
														'att_name' - name of attribute
														'att_desc' - human readable name of attribute
   opt['smarty_form'] 			(form)			phplib html form
   
   opt['smarty_action']			(action)		tells what should smarty display. Values:
   												'default' - 
												'was_updated' - when user submited form and data was succefully stored
*/
 
class apu_user_preferences extends apu_base_class{
	var $reg;			//Creg class
	var $usr_pref;		//User_preferences class
	var $attributes;	//array of cattrib objects
	var $smarty_action='default';
	var $js_on_subm=""; //javascript which is called on form submit

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_attributes', 'get_att_values', 'update_attribute_of_user');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array("sip_address_completion.js.php");
	}
	
	/* constructor */
	function apu_user_preferences(){
		global $lang_str, $config;
		parent::apu_base_class();

		/* with which attributes we will work, if this array is ampty we will work with all defined attributes */		
		$this->opt['attributes'] = array();	
		$this->opt['exclude_attributes'] = array();	

		$this->opt['att_description'] = array();	

		$this->opt['error_messages'] = array();	
		$this->opt['validate_funct'] = null;	

		$this->opt['optionals'] = array();	
		
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

	/* 
		returned two dimensional array of attributes
		it is ordered array which elements contain associative array, with keys:
			'att_name'
			'att_desc'
			'att_type'
			'att_spec' (for type 'radio' only)
		
		this method also return javascript on form submit for attributes which type is sip address
	 */
	function format_attributes_for_output($attributes, &$js_on_subm){
		global $sess;
	
		$out=array();
		foreach($attributes as $att){
			$name_of_attribute = &$this->attributes[$att]->att_name;
		
			if ($this->attributes[$att]->att_rich_type == "sip_adr") 
					$js_on_subm.="sip_address_completion(f.".$name_of_attribute.");";

			// set description of attributes
			if (isset($this->opt['att_description'][$name_of_attribute]))
				$out[$name_of_attribute]['att_desc'] = $this->opt['att_description'][$name_of_attribute];
			else
				$out[$name_of_attribute]['att_desc'] = $name_of_attribute;
	
			$out[$name_of_attribute]['att_name'] = $name_of_attribute;

			$out[$name_of_attribute]['att_type'] = $this->attributes[$att]->att_rich_type;
			
			/* if type of attribute is radio, create list of options
			 * ass array of asociative arrays with entries 'label' and 'value'
			 */
			if ($this->attributes[$att]->att_rich_type == "radio") {
				foreach($this->f->elements[$name_of_attribute]['ob']->options as $v){
					$out[$name_of_attribute]['att_spec'][] = $v;
				}
			}

		}
		return $out;
	}
	
	function action_update(&$errors){
		global $_POST, $_SERVER, $data, $sess;

		//update all changed attributes
		foreach($this->opt['attributes'] as $att){
			//if att value is changed
			if ($_POST[$this->attributes[$att]->att_name] != $_POST["_hidden_".$this->attributes[$att]->att_name]){
				if (false === $data->update_attribute_of_user($this->user_id, 
																$this->attributes[$att]->att_name, 
																$_POST[$this->attributes[$att]->att_name], 
																$errors)) 
					return false;
			}
		}

		return array("m_usr_pref_updated=".RawURLEncode($this->opt['instance_id']));
	}
	
		
	/* this metod is called always at begining */
	function init(){
		global $_SERWEB;
		parent::init();

		$this->reg = new Creg;				// create regular expressions class
		$this->usr_pref = new User_Preferences();

	}
	
	/* check _get and _post arrays and determine what we will do */
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
	
	/* create html form */
	function create_html_form(&$errors){
		global $data, $config;
		parent::create_html_form($errors);

		do{		
			//get list of attributes
			if (false === $this->attributes = $data->get_attributes(NULL, $errors)) break;

			// if option 'atributes' is not given, that mean we will work with all attributes
			if (empty($this->opt['attributes'])){
				foreach($this->attributes as $att){
					$this->opt['attributes'][] = $att->att_name;
				}
			}
			//else check if all opt['attributes'] is known
			else {
				foreach($this->opt['attributes'] as $k=>$v){
					if (!array_key_exists($v, $this->attributes)){
                        log_errors(PEAR::RaiseError("Attribute named '".$v."' does not exists"), $errors); 
						unset($this->opt['attributes'][$k]);
					}
				}
			}

			//except unwanted arguments
			$this->opt['attributes'] = array_diff($this->opt['attributes'], $this->opt['exclude_attributes']);


			// get attributes values
			if (false === $data->get_att_values($this->user_id, $this->attributes, $errors)) break;

			// add elements to form object
			foreach($this->opt['attributes'] as $att){
				$this->usr_pref->form_element($this->f, 
								$this->attributes[$att]->att_name, 
								$this->attributes[$att]->att_value, 
								$this->attributes[$att]->att_rich_type, 
								$this->attributes[$att]->att_type_spec,
								(isset($this->opt['optionals'][$this->attributes[$att]->att_name])) ? 
									($this->opt['optionals'][$this->attributes[$att]->att_name]) : 
									false,
								(isset($this->opt['error_messages'][$this->attributes[$att]->att_name])) ? 
									($this->opt['error_messages'][$this->attributes[$att]->att_name]) : 
									null
								);
			}
		
		} while (false);

	}

	/* validate html form */
	function validate_form(&$errors){
		global $_POST, $lang_str;
		if (false === parent::validate_form($errors)) return false;

		//check values of attributes and format its
		foreach($this->opt['attributes'] as $att){
			if (!$this->usr_pref->format_inputed_value($_POST[$this->attributes[$att]->att_name], 
														$this->attributes[$att]->att_rich_type, 
														$this->attributes[$att]->att_type_spec)){
				// value of attribute is wrong
				// if is set error message for this attribut, add it to $errors array
				// otherwise add to $errors array default message: $lang_str['fe_invalid_value_of_attribute']
				
				if (isset($this->opt['error_messages'][$this->attributes[$att]->att_name]))
					$errors[]=$this->opt['error_messages'][$this->attributes[$att]->att_name];
				else
					$errors[]=$lang_str['fe_invalid_value_of_attribute']." ".$this->attributes[$att]->att_name; 

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
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_usr_pref_updated']) and $_GET['m_usr_pref_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}

	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		
		$smarty->assign($this->opt['smarty_attributes'], 
		                $this->format_attributes_for_output($this->opt['attributes'], $this->js_on_subm));
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
	}

	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'before'      => $this->js_on_subm
		            );
	}

}

?>

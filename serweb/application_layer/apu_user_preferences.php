<?
/*
 * $Id: apu_user_preferences.php,v 1.5 2004/08/28 15:38:19 kozlik Exp $
 */ 

/* Application unit user preferences */

/*
   This application unit is used for view and change values in table user preferences
   
   Configuration:
   --------------
   'attributes'					(array) with which attributes should object work - default with all atributes
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
								
   'msg_update'					message which should be showed on attributes update - assoc array with keys 'short' and 'long'
   								default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
   'smarty_attributes'			name of smarty variable - see below
   'smarty_form'				name of smarty variable - see below
   'smarty_action'				name of smarty variable - see below
   'form_name'					name of html form
   
   Exported smarty variables:
   --------------------------
   opt['smarty_attributes'] 	(attributes)	associative array containing info about attributes
   													key: 'att_name' - name of attribute
   opt['smarty_form'] 			(form)			phplib html form
   
   opt['smarty_action']			(action)		tells what should smarty display. Values:
   												'default' - 
												'was_updated' - when user submited form and data was succefully stored
*/
 
class apu_user_preferences extends apu_base_class{
	var $f; 			//html form
	var $reg;			//Creg class
	var $usr_pref;		//User_preferences class
	var $attributes;	//array of cattrib objects
	var $smarty_action='default';

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
		global $lang_str;
		parent::apu_base_class();

		/* with which attributes we will work, if this array is ampty we will work with all defined attributes */		
		$this->opt['attributes'] = array();	

		$this->opt['error_messages'] = array();	
		$this->opt['validate_funct'] = null;	

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
		it is ordered array which elements contain associative array, curently with key 'att_name' only
		
		this method also return javascript on form submit for attributes which type is sip address
	 */
	function format_attributes_for_output($attributes, &$js_on_subm){
		global $sess;
	
		$out=array();
		$i=0;
		foreach($attributes as $att){
			if ($this->attributes[$att]->att_rich_type == "sip_adr") 
					$js_on_subm.="sip_address_completion(f.".$this->attributes[$att]->att_name.");";
	
			$out[$i]['att_name'] = $this->attributes[$att]->att_name;
			$i++;
		}
		
		return $out;
	}
	

	function action_update(&$errors){
		global $_POST, $_SERVER, $lang_str, $data, $sess;
	
		if ($err = $this->f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			return false;
		}

		// Process data            // Data ok;

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

        Header("Location: ".$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&m_usr_pref_updated=".RawURLEncode($this->opt['instance_id'])));
		page_close();
		exit;
	}
	
		
	/* this metod is called always at begining */
	function init(){
		global $_SERWEB;
		require_once ($_SERWEB["serwebdir"] . "user_preferences.php");
		
		$this->reg = new Creg;				// create regular expressions class
		$this->f = new form;                // create a form object
		$this->usr_pref = new User_Preferences();

	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if (isset($_POST['okey_x']) and isset($_POST['apu_name']) and $_POST['apu_name']==$this->opt['instance_id']){	// Is there data to process?
			$this->action="update";
		}
		else $this->action="";
	}
	
	/* realize action */
	function execute(&$errors){
		global $data, $config;
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

			// get attributes values
			if (false === $data->get_att_values($this->user_id, $this->attributes, $errors)) break;

			// add elements to form object
			foreach($this->opt['attributes'] as $att){
				$this->usr_pref->form_element($this->f, 
								$this->attributes[$att]->att_name, 
								$this->attributes[$att]->att_value, 
								$this->attributes[$att]->att_rich_type, 
								$this->attributes[$att]->att_type_spec);
			}
		
			$this->f->add_element(array("type"=>"hidden",
			                             "name"=>"apu_name",
			                             "value"=>$this->opt['instance_id']));

			$this->f->add_element(array("type"=>"submit",
			                             "name"=>"okey",
			                             "src"=>$config->img_src_path."butons/b_save.gif",
										 "extrahtml"=>"alt='save'"));
										 
			if ($this->action == 'update') 
				if (false === $this->action_update($errors)) {
					//data isn't valid or error in sql - Load form with submitted data
					$this->f->load_defaults(); 
					break;
				}
		} while (false);
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
		
		$js_on_subm="";
		$smarty->assign($this->opt['smarty_attributes'], $this->format_attributes_for_output($this->opt['attributes'], $js_on_subm));
		$smarty->assign_phplib_form($this->opt['smarty_form'], 
									$this->f, 
									array('jvs_name'=>'form_'.$this->opt['instance_id'],
									      'form_name'=>$this->opt['form_name']), 
									array('before'=>$js_on_subm));
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
	}
}

?>
<?php
/**
 * Application unit attributes
 * 
 * @author     Karel Kozlik
 * @version    $Id: apu_attributes.php,v 1.17 2009/10/01 09:42:54 kozlik Exp $
 * @package    serweb
 * @subpackage mod_attributes
 */ 

/**
 *	This application unit is used for view and change values of attributes
 *	
 *	<pre>
 *	Configuration:
 *	--------------
 *	'attributes'					(array) default: array containing all atributes 
 *	 array containing attributes which should be displayed
 *
 *	'exclude_attributes'			(array) default: array()
 *	 array containing attributes which shouldn't be displayed
 *
 *	'attrs_group'				(string) default: null
 *	 if is set, work only with attributes from specified group
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
 *	'attrs_options'				(array) default: array()
 *	 Various options for attributes. Array is indexed by attr names. Each
 *	 element have to contain another associative array of options.
 *	
 *		 example:
 *			array ('lang' => array('save_to_cookie' => true,
 *			                       'save_to_session' => true),
 *			       'foo'  => array('foobar => 42'));
 *	
 *								
 *	'redirect_on_update'		(string) default: ''
 *	 name of script to which is browser redirected after succesfull update
 *	 if empty, browser isn't redirected
 *
 *	'on_update_callback'		(string) default: null
 *	 name of function called when action 'update' is invoked
 *	
 *	'on_default_callback'		(string) default: null
 *	 name of function called when action 'default' is invoked
 *	
 *	'msg_update'					default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
 *	 message which should be showed on attributes update - assoc array with keys 'short' and 'long'
 *
 *
 *	'smarty_attributes'			name of smarty variable - see below
 *	'smarty_form'				name of smarty variable - see below
 *	'smarty_action'				name of smarty variable - see below
 *	'form_name'					name of html form
 *	
 *	'form_submit'				(assoc)
 *	  assotiative array describe submit element of form. For details see description 
 *	  of method add_submit in class form_ext
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_attributes'] 	(attributes)	associative array containing info about attributes
 *													keys: 
 *														'att_name' - name of attribute
 *														'att_desc' - human readable name of attribute
 *	
 *	opt['smarty_form'] 			(form)			phplib html form
 *	
 *	opt['smarty_action']		(action)		tells what should smarty display. Values:
 *												'default' - 
 *												'was_updated' - when user submited form and data was succefully stored
 *	
 *	</pre>
 *	@package    serweb
 *	@subpackage mod_attributes
 */
 
class apu_attributes extends apu_base_class{
	var $uid = null;
	var $did = null;
	var $attr_types;	
	var $user_attrs;	
	var $domain_attrs;	
	var $global_attrs;	
	var $smarty_action = 'default';
	var $js_on_subm = ""; //javascript which is called on form submit
	var $js_on_subm_2 = ""; //javascript which is called on form submit
	/** list of all avaiable attributes before they are filtered by group */
	var $all_avaiable_attrs = array();

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_attr_types', 'get_user_attrs', 'get_domain_attrs', 'get_global_attrs');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array("sip_address_completion.js.php");
	}
	
	/* constructor */
	function apu_attributes(){
		global $lang_str, $config;
		parent::apu_base_class();

		/* with which attributes we will work, if this array is ampty we will work with all defined attributes */		
		$this->opt['attributes'] = array();	
		$this->opt['exclude_attributes'] = array();	
		$this->opt['attrs_group'] = null;

		$this->opt['error_messages'] = array();	
		$this->opt['validate_funct'] = null;	
		$this->opt['attrs_options'] = array();	
		$this->opt['validate_js_funct'] = null;	

		$this->opt['attrs_kind'] = 'user';	
		$this->opt['redirect_on_update']  = "";
		$this->opt['perm'] = 'user';	

		$this->opt['on_default_callback'] = null;
		$this->opt['on_update_callback'] = null;

		$this->opt['allow_edit'] = true;
		
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

	/* this metod is called always at begining */
	function init(){
		global $_SERWEB;
		parent::init();

		switch($this->opt['attrs_kind']){
		case "uri":
			$this->uri_scheme = $this->controler->get_interapu_var('uri_scheme');
			$this->uri_uname = $this->controler->get_interapu_var('uri_uname');
			$this->uri_did = $this->controler->get_interapu_var('uri_did');
			$this->uid = $this->controler->user_id->get_uid();
			$this->did = $this->uri_did;
			break;
		case "user":
			$this->uid = $this->controler->user_id->get_uid();
			$this->did = $this->controler->user_id->get_did();
			break;
		case "domain":
			$this->did = $this->controler->domain_id;
			break;
		}
	}
	
	/*
	 *	Return list of attributes used by this APU (not filtered by group)
	 *	
	 *	Notice: Attributes are avaiable after method create_html_form is called
	 */
	function get_all_avaiable_attributes(){
		return $this->all_avaiable_attrs;
	}

	function access_to_change($at_name){
		switch($this->opt['perm']){
		case "user":
			return $this->attr_types[$at_name]->get_user_access_to_change();

		case "admin":
			return $this->attr_types[$at_name]->get_admin_access_to_change();

		case "hostmaster":
			return $this->attr_types[$at_name]->get_hostmaster_access_to_change();
		}
		
		return false;
	}
	
	function access_to_read($at_name){
		switch($this->opt['perm']){
		case "user":
			return $this->attr_types[$at_name]->get_user_access_to_read();

		case "admin":
			return $this->attr_types[$at_name]->get_admin_access_to_read();

		case "hostmaster":
			return $this->attr_types[$at_name]->get_hostmaster_access_to_read();
		}
		
		return false;
	}

	/**
	 *	returned two dimensional array of attributes
	 *	it is ordered array which elements contain associative array, with keys:
	 *		'att_name'
	 *		'att_desc'
	 *		'att_type'
	 *		'att_spec' (for type 'radio' only)
	 *	
	 *	this method also return javascript on form submit for attributes which type is sip address
	 *	
	 *	@access private
	 */
	function format_attributes_for_output(){
	
		$out=array();
		foreach($this->opt['attributes'] as $att){

			if ($this->attr_types[$att]->get_type() == "sip_adr")		
					$this->js_on_subm.="sip_address_completion(f.".$att.");";

			// set description of attributes
//			if (isset($this->opt['att_description'][$name_of_attribute]))
//				$out[$name_of_attribute]['att_desc'] = $this->opt['att_description'][$name_of_attribute];
//			else
//				$out[$name_of_attribute]['att_desc'] = $name_of_attribute;

			$out[$att]['att_desc'] = $this->attr_types[$att]->get_description();
			$out[$att]['att_ldesc'] = $this->attr_types[$att]->get_long_description();
			$out[$att]['att_name'] = $att;
			$out[$att]['att_type'] = $this->attr_types[$att]->get_type();
			$out[$att]['att_value'] = $this->attr_values[$att];
			$out[$att]['att_value_f'] = $this->attr_types[$att]->format_value($this->attr_values[$att]);
			$out[$att]['att_grp'] = $this->attr_types[$att]->get_group();
			$out[$att]['edit'] = $this->access_to_change($att);
			
			/* if type of attribute is radio, create list of options
			 * ass array of asociative arrays with entries 'label' and 'value'
			 */
			if ($this->attr_types[$att]->get_type() == "radio") {
				foreach($this->f->elements[$att]['ob']->options as $v){
					$out[$att]['att_spec'][] = $v;
				}
			}

		}
		return $out;
	}
	
	function action_update(&$errors){
		global $data;

		//update all changed attributes
		foreach($this->opt['attributes'] as $att){
			//skip attributes which has not access to change
			if (!$this->access_to_change($att)) continue;
			//if att value is changed
			if ($_POST[$att] != $_POST["_hidden_".$att]){
				
				switch($this->opt['attrs_kind']){
				case "uri":
					if (false === $this->uri_attrs->set_attribute($att, $_POST[$att])) return false;
				break;
				case "user":
					if (false === $this->user_attrs->set_attribute($att, $_POST[$att])) return false;
				break;
				case "domain":
					if (false === $this->domain_attrs->set_attribute($att, $_POST[$att])) return false;
				break;
				case "global":
					if (false === $this->global_attrs->set_attribute($att, $_POST[$att])) return false;
				break;
				}
			}
		}

		if ($this->opt['on_update_callback']){
			call_user_func_array($this->opt['on_update_callback'], array(&$this));
		}

		if ($this->opt['redirect_on_update']){
			$this->controler->change_url_for_reload($this->opt['redirect_on_update']);
		}

		return array("m_attrs_updated=".RawURLEncode($this->opt['instance_id']));
	}


	function action_default(&$errors){

		if ($this->opt['on_default_callback']){
			call_user_func_array($this->opt['on_default_callback'], array(&$this));
		}

	}	
			
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if ($this->was_form_submited() and $this->opt['allow_edit']){	// Is there data to process?
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

		$attr_types = &Attr_types::singleton();
	
		//get list of attributes
		if (false === $this->attr_types = &$attr_types->get_attr_types()) return false;

		switch($this->opt['attrs_kind']){
		case "uri":
			// get uri_attrs
			$this->uri_attrs = &Uri_Attrs::singleton($this->uri_scheme, $this->uri_uname, $this->uri_did);
			if (false === $uri_attrs = $this->uri_attrs->get_attributes()) return false;

		case "user":
			// get user_attrs
			$this->user_attrs = &User_Attrs::singleton($this->uid);
			if (false === $user_attrs = $this->user_attrs->get_attributes()) return false;

		case "domain":
			// get domain_attrs
			$this->domain_attrs = &Domain_Attrs::singleton($this->did);
			if (false === $domain_attrs = $this->domain_attrs->get_attributes()) return false;

		case "global":
			// get global_attrs
			$this->global_attrs = &Global_Attrs::singleton();
			if (false === $global_attrs = $this->global_attrs->get_attributes()) return false;
		}

		$this->attr_values = array();
		foreach($this->attr_types as $k => $v){
			if     ($this->opt['attrs_kind'] == 'uri' and 
			        !$this->attr_types[$k]->is_for_URIs()) 
				    continue;

			elseif ($this->opt['attrs_kind'] == 'user' and 
			        !$this->attr_types[$k]->is_for_users()) 
				    continue;

			elseif ($this->opt['attrs_kind'] == 'domain' and 
			        !$this->attr_types[$k]->is_for_domains()) 
					continue;

			elseif ($this->opt['attrs_kind'] == 'global' and 
			        !$this->attr_types[$k]->is_for_globals()) 
					continue;

		
			switch($this->opt['attrs_kind']){
			case "uri":
				if (isset($uri_attrs[$k])){
					$this->attr_values[$k] = $uri_attrs[$k];
					break;
				}		
			case "user":
				if (isset($user_attrs[$k])){
					$this->attr_values[$k] = $user_attrs[$k];
					break;
				}		
			case "domain":
				if (isset($domain_attrs[$k])){
					$this->attr_values[$k] = $domain_attrs[$k];
					break;
				}		
			case "global":
				if (isset($global_attrs[$k])){
					$this->attr_values[$k] = $global_attrs[$k];
					break;
				}
			}
			
			/*
			 *	If the value of attribute is not found, set it as null
			 */
			if (!isset($this->attr_values[$k])) $this->attr_values[$k] = null;	
					
		}

		// if option 'atributes' is not given, that mean we will work with all attributes
		if (empty($this->opt['attributes'])){
			foreach($this->attr_values as $k => $v){
				// work only with attributes which have access to read
				if ($this->access_to_read($k)){
					$this->opt['attributes'][] = $k;
				}
			}
		}
		//else check if all opt['attributes'] is known
		else {
			foreach($this->opt['attributes'] as $k=>$v){
				if (!array_key_exists($v, $this->attr_values)){
                    log_errors(PEAR::RaiseError("Attribute named '".$v."' does not exists"), $errors); 
					unset($this->opt['attributes'][$k]);
				}
			}
		}

		//except unwanted arguments
		$this->opt['attributes'] = array_diff($this->opt['attributes'], $this->opt['exclude_attributes']);

		//save avaiable attrs before are filtered by group
		$this->all_avaiable_attrs = $this->opt['attributes'];

		if (!empty($this->opt['attrs_group'])){
			foreach($this->opt['attributes'] as $k=>$v){
				// work only with attributes from specified group
				if ($this->attr_types[$v]->get_group() != $this->opt['attrs_group']){
					unset($this->opt['attributes'][$k]);
				}
			}
		}

		//set options to attributes
		foreach($this->opt['attributes'] as $att){
			if (isset($this->opt['attrs_options'][$att]) and 
			    is_array($this->opt['attrs_options'][$att])){
			
				foreach($this->opt['attrs_options'][$att] as $k => $v){
					$this->attr_types[$att]->set_opt($k, $v);
				}
			}
		}

		// add elements to form object
		foreach($this->opt['attributes'] as $att){
			if (!$this->access_to_change($att)) continue; //if attribute cannot be changed, do not add it ot the form

			$opt = array();
			$opt['err_msg']  = isset($this->opt['error_messages'][$att]) ? $this->opt['error_messages'][$att] : null;

			$this->attr_types[$att]->form_element($this->f, 
			                                      $this->attr_values[$att],
			                                      $opt);
			                                      
			$this->js_on_subm   .= $this->attr_types[$att]->validation_js_before();
			$this->js_on_subm_2 .= $this->attr_types[$att]->validation_js_after();
		}

		if (!empty($this->opt['validate_js_funct'])) $this->js_on_subm_2 .= $this->opt['validate_js_funct'];
	}

	/* validate html form */
	function validate_form(&$errors){
		global $lang_str;
		if (false === parent::validate_form($errors)) return false;

		//check values of attributes and format its
		foreach($this->opt['attributes'] as $att){
			if (!$this->access_to_change($att)) continue; //if attribute cannot be changed, do not validate it

			if (!$this->attr_types[$att]->check_value($_POST[$att])){

				// value of attribute is wrong
				// if is set error message for this attribute, add it to $errors array
				// otherwise add to $errors array default message: $lang_str['fe_invalid_value_of_attribute']
				
				if (isset($this->opt['error_messages'][$att]))
					$errors[]=$this->opt['error_messages'][$att];
				elseif (!is_null($this->attr_types[$att]->get_err_msg())){
					$errors[]=$this->attr_types[$att]->get_err_msg(); 
				}
				else
					$errors[]=$lang_str['fe_invalid_value_of_attribute']." ".$this->attr_types[$att]->get_description(); 

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
		
		if (isset($_GET['m_attrs_updated']) and $_GET['m_attrs_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		
		$smarty->assign($this->opt['smarty_attributes'], 
		                $this->format_attributes_for_output());
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
	}

	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'before'      => $this->js_on_subm,
		             'after'       => $this->js_on_subm_2
		            );
	}

}

?>

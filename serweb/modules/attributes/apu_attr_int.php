<?php

/**
 * Application unit apu_attr_int 
 * 
 * @author     Karel Kozlik
 * @version    $Id: apu_attr_int.php,v 1.2 2007/02/14 16:36:40 kozlik Exp $
 * @package    serweb
 * @subpackage mod_attributes
 */ 


/**
 *	Application unit apu_attr_int 
 *
 *
 *	This application unit is used for edit 'type_spec' field of 'int' attributes
 *	   
 *	<pre>
 *	Configuration:
 *	--------------
 *	
 *	'attr_name'					(string)
 *	 name of attribute which type_spec will be edited
 *	 This option is required!
 *	
 *	'attr_types_script'			(string)	default: 'attr_types.php'
 *	 script for editing attributes
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
 *	'smarty_url_back'			name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_action']			(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'was_updated' - when user submited form and data was succefully stored
 *	
 *	opt['smarty_url_back']			(url_back)
 *	  containing URL of script for edit attributes
 *	
 *	</pre>
 *	@package    serweb
 *	@subpackage mod_attributes
 */

class apu_attr_int extends apu_base_class{
	var $smarty_action='default';
	/** currently edited item of list */
	var $item_id = null;
	/** array containing specifications of int attribute */
	var $type_spec;
	/** instance of Attr_type */
	var $at;

	/** 
	 *	return required data layer methods - static class 
	 *
	 *	@return array	array of required data layer methods
	 */
	function get_required_data_layer_methods(){
		return array('update_attr_type');
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
	function apu_attr_int(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['attr_types_script'] = 	"attr_types.php";
		$this->opt['attr_name'] = 			'';


		/* message on attributes update */
		$this->opt['msg_update']['short'] =	&$lang_str['msg_changes_saved_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_changes_saved_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';
		
		$this->opt['smarty_url_back'] = 	'url_back';
		
	}

	/**
	 *	this metod is called always at begining - initialize variables
	 */
	function init(){
		parent::init();
	}
	
	function get_template_name(){
		return "int";
	}

	function update_attr_type(){
		global $data;

		$this->at->set_type_spec($this->type_spec);
		if (false === $data->update_attr_type($this->at, $this->at->get_name(), null)) return false;
		return true;
	}
	
	/**
	 *	Method perform action update
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_update(&$errors){

		if ($_POST['at_int_min'] == '') $_POST['at_int_min']=null;
		if ($_POST['at_int_max'] == '') $_POST['at_int_max']=null;
		if ($_POST['at_int_err'] == '') $_POST['at_int_err']=null;

		$this->type_spec['min'] = $_POST['at_int_min'];
		$this->type_spec['max'] = $_POST['at_int_max'];
		$this->type_spec['err'] = $_POST['at_int_err'];

		if (false === $this->update_attr_type()) return false;

		$this->controler->change_url_for_reload($this->opt['attr_types_script']);

		return true;
	}
	
	/**
	 *	check _get and _post arrays and determine what we will do 
	 */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			$this->action = array('action'=>"update",
		                          'validate_form'=>true,
			                      'reload'=>true);
			return;
		}

		$this->action=array('action'=>"default",
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
		parent::create_html_form($errors);
		global $lang_str;


		/* get info about edited attribute */
		$attrs = &Attr_types::singleton();
		if (false === $this->at = $attrs->get_attr_type($this->opt['attr_name'])){
			$errors[] = "unknown attribute";
			return false;
		}

		/* store items of list */
		$this->type_spec = $this->at->get_type_spec();
		if(!$this->type_spec) $this->type_spec = array();

		if (!isset($this->type_spec['min'])) $this->type_spec['min'] = null;
		if (!isset($this->type_spec['max'])) $this->type_spec['max'] = null;
		if (!isset($this->type_spec['err'])) $this->type_spec['err'] = null;


		$this->f->add_element(array("type"=>"text",
		                             "name"=>"at_int_min",
		                             "value"=>$this->type_spec['min'],
									 "size"=>16,
									 "maxlength"=>16,
									 "valid_regex"=>"^-?[0-9]*$",
									 "valid_e"=>"'".$lang_str['ff_at_int_min']."' ".$lang_str['fe_is_not_number']));
	
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"at_int_max",
		                             "value"=>$this->type_spec['max'],
									 "size"=>16,
									 "maxlength"=>16,
									 "valid_regex"=>"^-?[0-9]*$",
									 "valid_e"=>"'".$lang_str['ff_at_int_max']."' ".$lang_str['fe_is_not_number']));
	
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"at_int_err",
		                             "value"=>$this->type_spec['err'],
									 "size"=>16,
									 "maxlength"=>64));
	
	}

	/**
	 *	validate html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return bool			TRUE if given values of form are OK, FALSE otherwise
	 */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;
		return true;
	}
	
	
	/**
	 *	add messages to given array 
	 *
	 *	@param array $msgs	array of messages
	 */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_my_apu_updated']) and $_GET['m_my_apu_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}
	}

	/**
	 *	assign variables to smarty 
	 */
	function pass_values_to_html(){
		global $smarty, $sess;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_url_back'], $sess->url($this->opt['attr_types_script']));

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

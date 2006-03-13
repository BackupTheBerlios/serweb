<?php

/**
 * Application unit apu_attr_lists 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_attr_lists.php,v 1.1 2006/03/13 15:34:06 kozlik Exp $
 * @package   serweb
 */ 

/**
 *	Application unit apu_attr_lists 
 *
 *
 *	This application unit is used for edit 'type_spec' field of 'list' or 'radio' attributes
 *	   
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
 *	'smarty_items'				name of smarty variable - see below
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
 *	opt['smarty_items']			(items)
 *	  array containing list of items
 *	
 *	opt['smarty_url_back']			(url_back)
 *	  containing URL of script for edit attributes
 *	
 */

class apu_attr_lists extends apu_base_class{
	var $smarty_action='default';
	/** currently edited item of list */
	var $item_id = null;
	/** contain list of all items */
	var $item_list;
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
	function apu_attr_lists(){
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
		
		$this->opt['smarty_items'] = 		'items';

		$this->opt['smarty_url_back'] = 	'url_back';
		
	}

	/**
	 *	this metod is called always at begining - initialize variables
	 */
	function init(){
		parent::init();
	}
	
	function get_template_name(){
		return "lists";
	}

	function format_items_for_output(){
		global $sess;
	
		$out=array();
		$i=0;
		foreach($this->item_list as $value => $label){
			if ($value == $this->item_id) continue;
	
			$out[$i]['label'] = $label;
			$out[$i]['value'] = $value;
			$out[$i]['url_dele'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&at_dele=1&item=".RawURLEncode($value));
			$out[$i]['url_edit'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&at_edit=1&item=".RawURLEncode($value));
			$i++;
		}
	
		return $out;
	}

	function update_attr_type(){
		global $data;

		$this->at->set_type_spec($this->item_list);
		if (false === $data->update_attr_type($this->at, $this->at->get_name(), null)) return false;
		return true;
	}
	
	/**
	 *	Method perform action add
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_add(&$errors){

		$this->item_list[$_POST['at_item_value']] = $_POST['at_item_label'];

		if (false === $this->update_attr_type()) return false;
		return true;
	}
	
	/**
	 *	Method perform action update
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_update(&$errors){

		unset($this->item_list[$this->item_id]);
		$this->item_list[$_POST['at_item_value']] = $_POST['at_item_label'];

		if (false === $this->update_attr_type()) return false;
		return true;
	}
	
	/**
	 *	Method perform action delete
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_delete(&$errors){

		unset($this->item_list[$this->item_id]);

		if (false === $this->update_attr_type()) return false;
		return true;
	}
	
	
	/**
	 *	Method perform action edit
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_edit(&$errors){
		return true;
	}
	
	/**
	 *	check _get and _post arrays and determine what we will do 
	 */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			if ($_POST['at_item_id']){
				$this->item_id = $_POST['at_item_id'];

				$this->action = array('action'=>"update",
			                          'validate_form'=>true,
				                      'reload'=>true);
			}
			else {
				$this->action = array('action'=>"add",
			                          'validate_form'=>true,
				                      'reload'=>true);
			}
			return;
		}

		if (isset($_GET['at_edit'])){
			$this->item_id = $_GET['item'];

			$this->action = array('action'=>"edit",
			                      'validate_form'=>false,
			                      'reload'=>false);
			return;
		}

		if (isset($_GET['at_dele'])){
			$this->item_id = $_GET['item'];

			$this->action = array('action'=>"delete",
			                      'validate_form'=>false,
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
		$this->item_list = $this->at->get_type_spec();
		if(!$this->item_list) $this->item_list = array();

		$label = $value = "";

		/* if editing, set default values of form elements */
		if ($this->action['action'] == 'edit'){
			$label = isset($this->item_list[$this->item_id]) ?
						$this->item_list[$this->item_id] : 
						"";
			$value = $this->item_id;
		}


		$this->f->add_element(array("type"=>"text",
		                             "name"=>"at_item_label",
		                             "value"=>$label,
									 "size"=>16,
									 "maxlength"=>255,
									 "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_item_label']));
	
		$this->f->add_element(array("type"=>"text",
	    	                         "name"=>"at_item_value",
		                             "value"=>$value,
									 "size"=>16,
									 "maxlength"=>255,
									 "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_item_value']));
	
		$this->f->add_element(array("type"=>"hidden",
		                             "name"=>"at_item_id",
		                             "value"=>$this->item_id));
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
		$smarty->assign($this->opt['smarty_items'], $this->format_items_for_output());

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

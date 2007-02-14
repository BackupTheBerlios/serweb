<?php
/**
 * Application unit attr select group
 * 
 * @author     Karel Kozlik
 * @version    $Id: apu_attr_sel_grp.php,v 1.2 2007/02/14 16:36:40 kozlik Exp $
 * @package    serweb
 * @subpackage mod_attributes
 */ 

/**
 *	Application unit attr select group
 *
 *
 *	This application unit is used for select group of attributes
 *	   
 *	<pre>
 *	Configuration:
 *	--------------
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
 *	</pre>
 *	@package    serweb
 *	@subpackage mod_attributes
 */

class apu_attr_sel_grp extends apu_base_class{
	var $smarty_action='default';
	var $tabs = array();
	var $apu_attrs = null;
	var $selected_grp = array();

	/** 
	 *	return required data layer methods - static class 
	 *
	 *	@return array	array of required data layer methods
	 */
	function get_required_data_layer_methods(){
		return array();
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
	function apu_attr_sel_grp(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['select_name'] = null;
		$this->opt['initial_selected_grp'] = "general";

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
		
		$this->opt['smarty_groups'] =		'attr_groups';
		$this->opt['smarty_sel_group'] =	'attr_selected_group';
		
	}

	function set_apu_attrs(&$apu){
		$this->apu_attrs = &$apu;
	}
	
	function get_selected_grp(){
		return $this->session['selected_grp'];
	}

	/**
	 *	this metod is called always at begining - initialize variables
	 */
	function init(){
		parent::init();

		$session_name = empty($this->opt['filter_name'])?
		                $this->opt['instance_id']:
		                $this->opt['select_name'];

		if (!isset($_SESSION['apu_attr_sel_grp'][$session_name])){
			$_SESSION['apu_attr_sel_grp'][$session_name] = array();
		}
		
		$this->session = &$_SESSION['apu_attr_sel_grp'][$session_name];

		if (!isset($this->session['selected_grp'])){
			$this->session['selected_grp'] = $this->opt['initial_selected_grp'];
		}

		if (!is_a($this->apu_attrs, "apu_attributes")){
			die (__FILE__.":".__LINE__." - apu_attrs is not set or is not type of 'apu_attributes'. May be you forgot call method 'set_apu_attrs'");
		}
		
		$this->apu_attrs->set_opt("attrs_group", $this->session['selected_grp']);
	}
	
	/**
	 *	Method perform action update
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_update(&$errors){
		$this->session['selected_grp'] = $_GET['attr_grp'];
	
		return true;
	}
	
	/**
	 *	Method perform action default
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_default(&$errors){

		//get list of all atributes which could be displayed on tihis page
		$avail_attrs = $this->apu_attrs->get_all_avaiable_attributes();

		$at = &Attr_types::singleton();
		if (false === $grps = $at->get_attr_groups()) return false;
		if (false === $types = $at->get_attr_types()) return false;
		
		//make list of not empty groups 
		$avail_grps = array();
		foreach ($avail_attrs as $attr){
			$avail_grps[$types[$attr]->get_group()] = true;
		}
		
		
		$tabs = array();
		foreach($grps as $k => $v){
			//skip group with no attributes
			if (empty($avail_grps[$v])) continue;
			$page = $_SERVER['PHP_SELF']."?attr_grp=".RawURLEncode($v);
			$label = $at->get_attr_group_label($v);
			$tabs[] = new Ctab (true, $label, $page);
			if ($v == $this->session['selected_grp']) $this->selected_grp['tab'] = $page;
		}

		$this->selected_grp['grp'] = $this->session['selected_grp'];

		$this->tabs = &$tabs;		
	
		return true;
	}
	
	/**
	 *	check _get and _post arrays and determine what we will do 
	 */
	function determine_action(){
		if (isset($_GET['attr_grp'])){	// Is there data to process?
			$this->action=array('action'=>"update",
			                    'validate_form'=>false,
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
		parent::create_html_form($errors);
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
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_groups'], $this->tabs);
		$smarty->assign_by_ref($this->opt['smarty_sel_group'], $this->selected_grp);

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

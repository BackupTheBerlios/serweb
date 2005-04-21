<?php
/**
 * Application unit aliases
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_aliases.php,v 1.1 2005/04/21 15:09:45 kozlik Exp $
 * @package   serweb
 */ 

/** Application unit aliases
 *
 *
 *	This application unit is used for manipulation with aliases
 *	notice: manipulation still not dome. only get list of aliases may be used
 *	   
 *	Configuration:
 *	--------------
 *	'smarty_aliases'			name of smarty variable - see below
 *	
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_aliases'] 		(aliases)	
 *	 associative array containing user's aliases 
 *	 The array have same keys as function get_aliases (from data layer) returned. 
 *	
 */

class apu_aliases extends apu_base_class{
	var $smarty_action='default';
	var $aliases;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_aliases');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_aliases(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		

		
		/*** names of variables assigned to smarty ***/
		$this->opt['smarty_aliases'] =	'aliases';
		
	}

	function action_update(&$errors){
		return true;
	}

	function action_default(&$errors){
		global $data;
		if (false === $this->aliases = $data->get_aliases($this->user_id, $errors)) return false;
		return true;
	}
	
	/* this metod is called always at begining */
	function init(){
		parent::init();
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
//		if ($this->was_form_submited()){	// Is there data to process?
//			$this->action=array('action'=>"update",
//			                    'validate_form'=>true,
//								'reload'=>true);
//		}
//		else 
		$this->action=array('action'=>"default",
		                     'validate_form'=>false,
							 'reload'=>false);
	}
	
	/* create html form */
	function create_html_form(&$errors){
		parent::create_html_form($errors);
	}

	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;
		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;

		if(!$this->aliases) $this->aliases = array();

		$smarty->assign_by_ref($this->opt['smarty_aliases'], $this->aliases);

//		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
	}
	
}

?>

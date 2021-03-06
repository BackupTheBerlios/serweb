<?php
/**
 * Application unit acl (Access Control List)
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_acl.php,v 1.4 2006/04/14 10:27:40 kozlik Exp $
 * @package   serweb
 */ 

/** Application unit (Access Control List)
 *
 *
 *	This application unit is used for manipulation with Access Control List
 *	notice: manipulation still not dome. only get list may be used
 *	   
 *	Configuration:
 *	--------------
 *	'allow_edit'				(bool) default: false
 *   set true if instance of this APU should be used for change values
 *	 by default only get list of ACL is enabled
 *
 *	'redirect_on_update'		(string) default: ''
 *	 name of script to which is browser redirected after succesfull update
 *	 if empty, browser isn't redirected
 *
 *	'msg_update'				default: $lang_str['msg_acl_updated_s'] and $lang_str['msg_acl_updated_l']
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
 *	'smarty_acl'				name of smarty variable - see below
 *	'smarty_acl_control'		name of smarty variable - see below
 *	
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_form'] 				(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_action']			(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'was_updated' - when user submited form and data was succefully stored
 *
 *	opt['smarty_acl'] 				(acl)	
 *	 array containing user's Access Control List 
 *	
 *	opt['smarty_acl_control'] 		(acl_control)	
 *	 array containing list of ACLs which may currently logged user change
 *	
 */

class apu_acl extends apu_base_class{
	var $smarty_action='default';
	var $acl = array();
	/* list of ACLs which may admin change */
	var $acl_control = array();

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array();
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_acl(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['allow_edit'] =	false;
		$this->opt['redirect_on_update'] = "";

		/* message on attributes update */
		$this->opt['msg_update']['short'] =	&$lang_str['msg_acl_updated_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_acl_updated_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';
		
		$this->opt['smarty_acl'] =	'acl';

		$this->opt['smarty_acl_control'] = 'acl_control';
	}

	function action_update(&$errors){
		global $config;
		
		$an = &$config->attr_names;

		foreach ($this->acl_control as $row){
			// if value has been checked
			if (!empty($_POST["acl_chk_".$row]) and !in_array($row, $this->acl)){
				$this->acl[] = $row; //add value to the array
			}

			// if value has been unchecked
			if (empty($_POST["acl_chk_".$row]) and (false !== $k = array_search($row, $this->acl))){
				unset($this->acl[$k]); //remove value from array
			}
		}

		/* get user attrs object */
		$ua = &User_Attrs::singleton($this->user_id->get_uid());
		if (false === $ua->set_attribute($an['acl'], $this->acl)) return false;

		if ($this->opt['redirect_on_update'])
			$this->controler->change_url_for_reload($this->opt['redirect_on_update']);

		return array("m_acl_updated=".RawURLEncode($this->opt['instance_id']));
	}

	/* this metod is called always at begining */
	function init(){
		parent::init();
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){

		if ($this->opt['allow_edit'] and $this->was_form_submited()){	// Is there data to process?
			$this->action=array('action'=>"update",
			                    'validate_form'=>true,
								'reload'=>true);
		}
		else 
		$this->action=array('action'=>"default",
		                     'validate_form'=>false,
							 'reload'=>false);
	}
	
	/* create html form */
	function create_html_form(&$errors){
		global $config;
		parent::create_html_form($errors);

		$an = &$config->attr_names;

		$ua = &User_Attrs::singleton($this->user_id->get_uid());
		if (false === $this->acl = $ua->get_attribute($an['acl'])) return false;

		if (is_null($this->acl)) $this->acl = array();
		
		if ($this->opt['allow_edit']){

			/* get admin ACL control privileges */
			$user_attrs = &User_Attrs::singleton($_SESSION['auth']->get_uid());
			if (false === $this->acl_control = $user_attrs->get_attribute($an['acl_control'])) return false;
			
			if (is_null($this->acl_control)) $this->acl_control=array();		
			
			/* add form elements */
			foreach ($this->acl_control as $row){
				$this->f->add_element(array("type"=>"checkbox",
				                      "name"=>"acl_chk_".$row,
				                      "checked"=>in_array($row, $this->acl)?"1":"0",
				                      "value"=>"1"));
			}
		}
	}

	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;
		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		if (isset($_GET['m_acl_updated']) and $_GET['m_acl_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;

		if(!$this->acl) $this->acl = array();

		$smarty->assign_by_ref($this->opt['smarty_acl'], $this->acl);
		$smarty->assign_by_ref($this->opt['smarty_acl_control'], $this->acl_control);

		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
	}

	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => '',
					 'before'      => '');
	}
}

?>

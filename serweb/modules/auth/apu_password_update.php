<?php
/**
 * Application unit password update
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_password_update.php,v 1.1 2005/12/22 13:46:09 kozlik Exp $
 * @package   serweb
 */ 

/** Application unit password update
 *	
 *	
 *	This application unit is used for changeing password of user
 *	
 *	Configuration:
 *	--------------
 *	
 *	'change_pass'				(bool) default: true
 *	  allow change password
 *	  
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
 *	'smarty_enabled_fields'		name of smarty variable - see below
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
 *	opt['smarty_enabled_fields']	(enabled_fields)
 *		boolean values reflecting setting of change_pass, change_email, etc..
 *		keys: pass, email, first_name, last_name, phone
 *	
 */

class apu_password_update extends apu_base_class{
	var $smarty_action='default';

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('set_password_to_user');
	}

	/* return array of strings - required javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_password_update(){
		global $lang_str, $config;
		parent::apu_base_class();

		/* set default values to $this->opt */		

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
		
	}

	/* this metod is called always at begining */
	function init(){
		parent::init();
	}

	function action_update(&$errors){
		global $data;
	
		//if password should be changed
		if ($_POST['pu_passwd']){
			if (false === $data->set_password_to_user($this->user_id, $_POST['pu_passwd'], $errors)) 
				return false;
		}

		return array("m_pu_updated=".RawURLEncode($this->opt['instance_id']));
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
		global $lang_str, $data;
		parent::create_html_form($errors);

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"pu_passwd",
		                             "value"=>"",
									 "size"=>16,
									 "maxlength"=>25,
									 "pass"=>1));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"pu_passwd_r",
		                             "value"=>"",
									 "size"=>16,
									 "maxlength"=>25,
									 "pass"=>1));
	}

	/* validate html form */
	function validate_form(&$errors){
		global $lang_str;
		if (false === parent::validate_form($errors)) return false;

		if ($_POST['pu_passwd'] and ($_POST['pu_passwd'] != $_POST['pu_passwd_r'])){
			$errors[]=$lang_str['fe_passwords_not_match']; break;
		}

		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_pu_updated']) and $_GET['m_pu_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		global $lang_str;
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'before'       => "
										if (f.pu_passwd.value!=f.pu_passwd_r.value){
											alert('".$lang_str['fe_passwords_not_match']."');
											f.pu_passwd.focus();
											return (false);
										}",
					 'after'      => '');
	}
}



?>

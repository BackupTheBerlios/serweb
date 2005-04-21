<?php
/**
 * Application unit personal_data
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_personal_data.php,v 1.1 2005/04/21 15:09:45 kozlik Exp $
 * @package   serweb
 */ 

/** Application unit personal_data
 *	
 *	
 *	This application unit is used for changeing personal data of user
 *	
 *	Configuration:
 *	--------------
 *	
 *	'change_pass'				(bool) default: true
 *	  allow change password
 *	  
 *	'change_email'				(bool) default: true
 *	  allow change email
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
 *		keys: pass, email
 *	
 */

class apu_personal_data extends apu_base_class{
	var $smarty_action='default';

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_sip_user_details', 'update_sip_user_details', 'get_time_zones', 'set_password_to_user');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_personal_data(){
		global $lang_str, $config;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['change_pass'] =			true;
		$this->opt['change_email'] =		true;

		/* message on attributes update */
		$this->opt['msg_update']['short'] =	&$lang_str['msg_changes_saved_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_changes_saved_l'];
		
		/*** names of variables assigned to smarty ***/
		/* enabled fields */
		$this->opt['smarty_enabled_fields'] = 'enabled_fields';
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';
		
	}

	function action_update(&$errors){
		global $data;
	
		$pass = $email = null;
	
		if ($this->opt['change_pass'] and $_POST['pd_passwd']) $pass=$_POST['pd_passwd'];	
	
		if ($this->opt['change_email']) $email=$_POST['pd_email'];
		

		if (false === $data->update_sip_user_details($this->user_id, $email, 
				isset($_POST['pd_allow_find'])?1:0, $_POST['pd_timezone'], 
				null, $errors)) return false;

		//if password should be changed
		if (!is_null($pass)){
			if (false === $data->set_password_to_user($this->user_id, $pass, $errors)) 
				return false;
		}

		return array("m_pd_updated=".RawURLEncode($this->opt['instance_id']));
	}
	
	/* this metod is called always at begining */
	function init(){
		parent::init();
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

		if (false === $user_data = $data->get_sip_user_details($this->user_id, $errors)) return false;

		$options=array();
		$opt=$data->get_time_zones($errors);
		foreach ($opt as $v) $options[]=array("label"=>$v,"value"=>$v);

		if ($this->opt['change_email']){
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"pd_email",
										 "size"=>16,
										 "maxlength"=>50,
			                             "valid_regex"=>$this->controler->reg->email,
			                             "valid_e"=>$lang_str['fe_not_valid_email'],
			                             "value"=>$user_data->email_address));
		}

		$this->f->add_element(array("type"=>"checkbox",
									 "checked"=>$user_data->allow_find,
		                             "value"=>1,
		                             "name"=>"pd_allow_find"));

		if ($this->opt['change_pass']){
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"pd_passwd",
			                             "value"=>"",
										 "size"=>16,
										 "maxlength"=>25,
										 "pass"=>1));
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"pd_passwd_r",
			                             "value"=>"",
										 "size"=>16,
										 "maxlength"=>25,
										 "pass"=>1));
		}

		$this->f->add_element(array("type"=>"select",
									 "name"=>"pd_timezone",
									 "options"=>$options,
									 "size"=>1,
		                             "value"=>$user_data->timezone));

	}

	/* validate html form */
	function validate_form(&$errors){
		global $lang_str;
		if (false === parent::validate_form($errors)) return false;

		if ($this->opt['change_pass']){
			if ($_POST['pd_passwd'] and ($_POST['pd_passwd'] != $_POST['pd_passwd_r'])){
				$errors[]=$lang_str['fe_passwords_not_match']; break;
			}

		}
		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_pd_updated']) and $_GET['m_pd_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		
		$smarty->assign($this->opt['smarty_enabled_fields'], 
				array('pass' => $this->opt['change_pass'],
				      'email' => $this->opt['change_email']));
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		global $lang_str;
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'before'       => ($this->opt['change_pass']?"
										if (f.pd_passwd.value!=f.pd_passwd_r.value){
											alert('".$lang_str['fe_passwords_not_match']."');
											f.pd_passwd.focus();
											return (false);
										}":""),
					 'after'      => '');
	}
}



?>

<?php
/**
 * Application unit forgotten_password
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_forgotten_password.php,v 1.6 2005/07/21 16:23:03 kozlik Exp $
 * @package   serweb
 */ 

/** Application unit forgotten_password
 *
 *	This application unit is used for .............
 *	   
 *	Configuration:
 *	--------------
 *
 *	'domain'					(string) default: $config->domain
 *	 domain to which users will be registered
 *	 
 *	'mail_file_conf'					(string) default: mail_forgot_password_conf.txt
 *	 name of file contining text of mail with login informations which is send 
 *
 *	'mail_file_pass'					(string) default: mail_forgot_password_pass.txt
 *	 name of file contining text of mail with new password which is send 
 *
 *	'msg_pass_send'					default: $lang_str['msg_password_sended_s'] and $lang_str['msg_password_sended_l']
 *	 message which should be showed on password succesfuly send - assoc array with keys 'short' and 'long'
 *								
 *	'msg_conf_send'					default: $lang_str['msg_pass_conf_sended_s'] and $lang_str['msg_pass_conf_sended_l']
 *	 message which should be showed on indtuctions to get new password succesfuly send - assoc array with keys 'short' and 'long'
 *								
 *	'fully_qualified_name'	(bool) default: $config->fully_qualified_name_on_login
 *	 true if should be entered fully qualifide username (username@domain)
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
 *	  phplib html form
 *	 
 *	opt['smarty_action']			(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'conf_was_sended' - when user submited form and login info was successfuly sended
 *	  'pass_was_sended' - when user confirm email and new password was successfuly sended
 *	
 */

class apu_forgotten_password extends apu_base_class{
	var $smarty_action='default';
	
	var $sip_user;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_sip_user', 'update_sip_user_confirmation', 
					'get_sip_user_by_confirm_nr', 'set_password_to_user');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array('login_completion.js.php');
	}
	
	/* constructor */
	function apu_forgotten_password(){
		global $config, $lang_str, $sess_lang;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['domain'] =				$config->domain;
		$this->opt['mail_file_conf'] =		"mail_forgot_password_conf.txt";
		$this->opt['mail_file_pass'] =		"mail_forgot_password_pass.txt";

		$this->opt['fully_qualified_name'] = $config->fully_qualified_name_on_login;

		/* message on attributes update */
		$this->opt['msg_conf_send']['short'] =	&$lang_str['msg_pass_conf_sended_s'];
		$this->opt['msg_conf_send']['long']  =	&$lang_str['msg_pass_conf_sended_l'];
		
		$this->opt['msg_pass_send']['short'] =	&$lang_str['msg_password_sended_s'];
		$this->opt['msg_pass_send']['long']  =	&$lang_str['msg_password_sended_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';
		
		$this->opt['form_submit']=array('type' => 'image',
										'text' => $lang_str['b_forgot_pass_submit'],
										'src'  => get_path_to_buttons("btn_get_pass.gif", $sess_lang));
	}

	function action_send_conf(&$errors){
		global $config, $data, $sess_lang, $lang_str, $pre_uid, $pre_uid_expires;

		$confirm=md5(uniqid(rand()));
	
		if (isModuleLoaded('xxl')){
			if (false === $proxy = $data->get_home_proxy($errors))
				return false;
		}

		if (false === $data->update_sip_user_confirmation(
						new Cserweb_auth($this->sip_user['uuid'],
						                 $this->sip_user['uname'],
										 $this->sip_user['domain']),
						$confirm,
						null,
						$errors)){
			return false;
		}

		$confirmation_url = $config->root_uri.
		                    $_SERVER['PHP_SELF'].
							"?nr=".$confirm.
							(isModuleLoaded('xxl') ? 
								"&pr=".RawURLEncode(base64_encode($proxy)):
								"");

		$mail = read_lang_txt_file($this->opt['mail_file_conf'], "txt", $sess_lang, 
					array(array("domain", $this->opt['domain']),
						  array("confirmation_url", $confirmation_url)));
					
		if ($mail === false){ 
			/* needn't write message to log. It's written by function read_lang_txt_file */
			$errors[]=$lang_str['err_sending_mail']; 
			return false;	
		}

		if (!send_mail($this->sip_user['email'], $mail['body'], $mail['headers'])){
			$errors[]=$lang_str['err_sending_mail']; 
			
			$this->controler->_form_load_defaults();
			return false;
		}

		return array("m_fp_conf_sended=".RawURLEncode($this->opt['instance_id']));

	}

	function action_send_pass(&$errors){
		global $data, $config, $sess_lang, $lang_str;
		
		if (isset($_GET['pr'])){
			$proxy = base64_decode($_GET['pr']);
			
			if ($proxy and isModuleLoaded('xxl')){
				if (false === $data->set_home_proxy($proxy)) return false;
			} 
		}
		
		if (isModuleLoaded('xxl') and !$proxy){
			$errors[] = $lang_str['err_reg_conf_not_exists_conf_num'];
			return false;
		}

		if (false === $sip_user=$data->get_sip_user_by_confirm_nr($this->nr, null, $errors)){ 
			$errors[] = $lang_str['err_reg_conf_not_exists_conf_num'];
			return false;
		}

		/* generate new password */
		$password = substr(md5(uniqid('')), 0, 5);

		if (false === $data->set_password_to_user(
						new Cserweb_auth($sip_user['uuid'],
						                 $sip_user['username'],
										 $sip_user['domain']),
						$password,  
						$errors)){ 
			return false;
		}

		$mail = read_lang_txt_file($this->opt['mail_file_pass'], "txt", $sess_lang, 
					array(array("domain", $this->opt['domain']),
						  array("password", $password)));
					
		if ($mail === false){ 
			/* needn't write message to log. It's written by function read_lang_txt_file */
			$errors[]=$lang_str['err_sending_mail']; 
			return false;	
		}

		if (!send_mail($sip_user['email_address'], $mail['body'], $mail['headers'])){
			$errors[]=$lang_str['err_sending_mail']; 
			return false;
		}

		return array("m_fp_pass_sended=".RawURLEncode($this->opt['instance_id']));
		
	}

	
	/* this metod is called always at begining */
	function init(){
		parent::init();
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if (isset($_GET['nr'])){
			$this->nr = $_GET['nr'];
			$this->action=array('action'=>"send_pass",
			                    'validate_form'=>false,
								'reload'=>true);
		}

		else if ($this->was_form_submited()){	// Is there data to process?
			$this->action=array('action'=>"send_conf",
			                    'validate_form'=>true,
								'reload'=>true);
		}
		else $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
	}
	
	/* create html form */
	function create_html_form(&$errors){
		global $lang_str;
		parent::create_html_form($errors);

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"fp_uname",
									 "size"=>20,
									 "maxlength"=>64,
		                             "value"=>"",
									 "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_username'],
									 "extrahtml"=>"autocomplete='off' ".
									 	($this->opt['fully_qualified_name'] ? " onBlur='login_completion(this)'" : "")));
	}

	/* validate html form */
	function validate_form(&$errors){
		global $config, $data, $lang_str;
		if (false === parent::validate_form($errors)) return false;

		//if fully quantified username is given
		if ($this->opt['fully_qualified_name']) {
			// parse username and domain from it
			if (ereg("^([^@]+)@(.+)", $_POST['fp_uname'], $regs)){
				$username=$regs[1];
				$domain=$regs[2];
				
			}
			else {
				sw_log("Get password failed: unsuported format of username. Can't parse username and domain part", PEAR_LOG_INFO);
				$errors[]=$lang_str['err_no_user'];
 				return false;
			}
		}
		else{
			$username=$_POST['fp_uname'];
			$domain=$this->opt['domain'];
		}

		$data->set_xxl_user_id('sip:'.$username.'@'.$domain);
		$data->expect_user_id_may_not_exists();

		if (false === $this->sip_user = $data->get_sip_user($username, $domain, $errors)) {
			$errors[] = $lang_str['err_no_user'];
			return false;
		}

		$this->sip_user['uname'] = $username;
		$this->sip_user['domain'] = $domain;

		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;

		if (isset($_GET['m_fp_pass_sended']) and $_GET['m_fp_pass_sended'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_pass_send'];
			$this->smarty_action="pass_was_sended";
		}

		if (isset($_GET['m_fp_conf_sended']) and $_GET['m_fp_conf_sended'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_conf_send'];
			$this->smarty_action="conf_was_sended";
		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => '',
					 'before'      => ($this->opt['fully_qualified_name'] ?
					 		'login_completion(f.fp_uname);':
							''));
	}
}

?>

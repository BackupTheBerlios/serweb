<?php
/**
 * Application unit forgotten_password
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_forgotten_password.php,v 1.5 2006/09/08 12:27:33 kozlik Exp $
 * @package   serweb
 */ 

/** Application unit forgotten_password
 *
 *	This application unit is used for .............
 *	   
 *	Configuration:
 *	--------------
 *
 *	'auth_class'				(string) default: "Auth"
 *	 Name of class auth class which is used for validate credentials.
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
	var $did = null;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('check_credentials', 'get_attr_by_val', 
		             'set_password_to_user', 'get_did_by_realm', 
					 'get_domain_flags');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array('login_completion.js.php');
	}
	
	/* constructor */
	function apu_forgotten_password(){
		global $config, $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['domain'] =				$config->domain;
		$this->opt['mail_file_conf'] =		"mail_forgot_password_conf.txt";
		$this->opt['mail_file_pass'] =		"mail_forgot_password_pass.txt";

		$this->opt['fully_qualified_name'] = $config->fully_qualified_name_on_login;

		$this->opt['auth_class'] = 'Auth';

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
										'src'  => get_path_to_buttons("btn_get_pass.gif", $_SESSION['lang']));
	}

	/* this metod is called always at begining */
	function init(){
		parent::init();
	}

	function set_from_header(&$headers){
		global $config;
		if (!is_null($this->did)){
			$opt = array("did"=>$this->did);
			if (false === $addr = Attributes::get_attribute($config->attr_names['contact_email'], $opt)) return false;
			
			if ($addr) $headers['from'] = $addr;
		}

		return true;	
	}
	
	function action_send_conf(&$errors){
		global $config, $data, $lang_str;

		$confirm=md5(uniqid(rand()));
		$an = &$config->attr_names;
	
		if (isModuleLoaded('xxl')){
			if (false === $proxy = $data->get_home_proxy($errors))
				return false;
		}

		$user_attrs = &User_Attrs::singleton($this->sip_user['uid']);
		if (false === $user_attrs->set_attribute($an['confirmation'], $confirm)) return false;

		if (false === $email = $user_attrs->get_attribute($an['email'])) return false;

		$serwebuser = &SerwebUser::instance($this->sip_user['uid'],
		                                    $this->sip_user['uname'],
											$this->sip_user['did'],
											$this->sip_user['realm']);

		$confirmation_url = $config->root_uri.
		                    $_SERVER['PHP_SELF'].
							"?nr=".$confirm.
							"&".$serwebuser->to_get_param("u").
							(isModuleLoaded('xxl') ? 
								"&pr=".RawURLEncode(base64_encode($proxy)):
								"");

		$mail = read_lang_txt_file($this->opt['mail_file_conf'], "txt", $_SESSION['lang'], 
					array(array("domain", $this->opt['domain']),
						  array("confirmation_url", $confirmation_url)));
					
		if ($mail === false){ 
			/* needn't write message to log. It's written by function read_lang_txt_file */
			$errors[]=$lang_str['err_sending_mail']; 
			return false;	
		}

		if (false === $this->set_from_header($mail['headers'])) return false;

		if (!send_mail($email, $mail['body'], $mail['headers'])){
			$errors[]=$lang_str['err_sending_mail']; 
			
			$this->controler->_form_load_defaults();
			return false;
		}

		return array("m_fp_conf_sended=".RawURLEncode($this->opt['instance_id']));

	}

	function action_send_pass(&$errors){
		global $data, $config, $lang_str;
		
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

		if (empty($_GET['u'])){
			$errors[] = $lang_str['err_reg_conf_not_exists_conf_num'];
			return false;
		}

		$an = &$config->attr_names;

		/* get uid */
		$o = array('name' =>  $an['confirmation'],
		           'value' => $this->nr);
		if (false === $attrs = $data->get_attr_by_val("user", $o)) return false;

		if (empty($attrs[0]['id'])) {
			ErrorHandler::add_error($lang_str['err_reg_conf_not_exists_conf_num']);
			return false;
		}

		$uid = $attrs[0]['id'];

		/* recreate instance of SerwebUser class from get param */
		$serweb_user = &SerwebUser::recreate_from_get_param($_GET['u']);

		/* and compare if uid obtained from user_attrs match to uid inside $serweb_user object */
		if ($uid != $serweb_user->get_uid()){
			ErrorHandler::add_error($lang_str['err_reg_conf_not_exists_conf_num']);
			return false;
		}

		/* get email address of user */
		$user_attrs = &User_Attrs::singleton($uid);
		if (false === $email = $user_attrs->get_attribute($an['email'])) return false;

		/* generate new password */
		$password = substr(md5(uniqid('')), 0, 5);

		if (false === $data->set_password_to_user(
						$serweb_user,
						$password,  
						$errors)){ 
			return false;
		}


		$mail = read_lang_txt_file($this->opt['mail_file_pass'], "txt", $_SESSION['lang'], 
					array(array("domain", $this->opt['domain']),
						  array("password", $password)));
					
		if ($mail === false){ 
			/* needn't write message to log. It's written by function read_lang_txt_file */
			$errors[]=$lang_str['err_sending_mail']; 
			return false;	
		}

		if (false === $this->set_from_header($mail['headers'])) return false;

		if (!send_mail($email, $mail['body'], $mail['headers'])){
			$errors[]=$lang_str['err_sending_mail']; 
			return false;
		}

		/* unset attribute confirmation */
		if (false === $user_attrs->unset_attribute($an['confirmation'])) return false;


		return array("m_fp_pass_sended=".RawURLEncode($this->opt['instance_id']));
		
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

		/* get did */
		$opt = array();
		$did = call_user_func_array(array($this->opt['auth_class'], 'find_out_did'), 
		                            array($domain, &$opt));

		if (false === $did) return false;
		if (is_null($did)){
			sw_log("Forgotten password: did not found for domain name: ".$domain, PEAR_LOG_DEBUG);
			ErrorHandler::add_error($lang_str['domain_not_found']);
			return false;
		}

		$o = array("did"=>$did);
		if (false === $realm = Attributes::get_attribute($config->attr_names['digest_realm'], $o)) return false;

		/* validate credentials */
		$o = array('check_pw' => false);
		$uid = call_user_func_array(array($this->opt['auth_class'], 'validate_credentials'), 
		                            array($username, $did, null, &$o));

		if (false === $uid) return false;

		$this->sip_user['uname'] = $username;
		$this->sip_user['realm'] = isset($o['realm']) ? $o['realm'] : "";
		$this->sip_user['uid']   = $uid;
		$this->sip_user['did']   = $did;

		$this->did = $did;

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

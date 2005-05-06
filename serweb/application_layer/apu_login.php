<?php
/**
 * Application unit login 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_login.php,v 1.6 2005/05/06 15:50:20 kozlik Exp $
 * @package   serweb
 */ 

/** Application unit login
 *
 *
 *	This application unit is used for login into application. This APU can't
 *	be combined with others APUs on one page.
 *	   
 *	Configuration:
 *	--------------
 *	'check_admin_privilege'		(bool) default: false
 *	 check if user has administrator privilege
 *	
 *	'fully_qualified_name_on_login'	(bool) default: $config->fully_qualified_name_on_login
 *	 trou if should be entered fully qualifide username (username@domain)
 *	
 *	'check_supported_domain_on_login'	(bool) default: false
 *	 Should be extra checked domain if is present in table domains 
 *	 in case that 'fully_qualified_name_on_login' is true
 *	
 *	'redirect_on_login'			(string) default: 'my_account.php'
 *	 name of script ot which is browser redirected after succesfull login
 *	
 *	'redirect_on_first_login'	(string) default: null
 *	 if is set, user is redirected to this script after his first login to serweb
 *	
 *	'cookie_domain'				(string) default: null
 *	 The domain that the cookie in which is stored username is available 
 *	
 *	'msg_logout'				default: $lang_str['msg_logout_s'] and $lang_str['msg_logout_l']
 *	 message which should be showed on user logout - assoc array with keys 'short' and 'long'
 *								
 *	'form_name'					(string) default: 'login_form'
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
 *	  'was_logged_out' - when user was logged out
 *	
 */

class apu_login extends apu_base_class{
	var $smarty_action='default';
	var $user_uuid = null;
	var $username = null;
	var $domain = null;
	var $password = null;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('domain_exists', 'is_user_registered', 'get_privileges_of_user');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array('login_completion.js.php');
	}
	
	/* constructor */
	function apu_login(){
		global $lang_str, $sess_lang, $config;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['fully_qualified_name_on_login'] = $config->fully_qualified_name_on_login;
		$this->opt['check_supported_domain_on_login'] = false;

		$this->opt['redirect_on_login'] = 'my_account.php';
		$this->opt['redirect_on_first_login'] = null;
		
		$this->opt['check_admin_privilege'] = false;

		$this->opt['cookie_domain'] = null;

		$this->opt['xxl_redirect_after_login'] = false;


		/* message on attributes update */
		$this->opt['msg_logout']['short'] =	&$lang_str['msg_logout_s'];
		$this->opt['msg_logout']['long']  =	&$lang_str['msg_logout_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'login_form';
		
		$this->opt['form_submit']=array('type' => 'image',
										'text' => $lang_str['b_login'],
										'src'  => get_path_to_buttons("btn_login.gif", $sess_lang));
		
	}

	function action_login(&$errors){
		global $data_auth, $lang_str, $sess, $config, $pre_uid;
		if ($sess->is_registered('auth')) $sess->unregister('auth');

		// set cookie only if not doing http redirect because
		// $_POST['remember_uname'] is not set during redirect
		if (!isset($_GET["redir_id"])){		
			if (isset($_POST['remember_uname']) and $_POST['remember_uname']) 
				setcookie('serwebuser', $_POST['uname'], time()+31536000, null, $this->opt['cookie_domain']); //cookie expires in one year
			else
				setcookie('serwebuser', '', time(), null, $this->opt['cookie_domain']); //delete cookie
		}

		if (isModuleLoaded('xxl') and $this->opt['xxl_redirect_after_login']){
			xxl_http_redirect(array("get_params"=>array(
										"uname"    => $this->username,
										"domain"   => $this->domain,
										"pass"     => $this->password,
										"redir_id" => $this->opt['instance_id'])));
		}

		$sess->register('pre_uid');
		$pre_uid=$this->user_uuid;
		
		if ($this->opt['redirect_on_first_login']){
		//check if user exists in subscriber table
			if (($registered = $data_auth->is_user_registered(new Cserweb_auth($pre_uid, $this->username, $this->domain), $errors)) < 0) return false;

			if (!$registered){
				sw_log("User login: first login of this user - redirecting to page: ".$this->opt['redirect_on_first_login'], PEAR_LOG_DEBUG);
				$this->controler->change_url_for_reload($this->opt['redirect_on_first_login']);
				return true;
			}
		} 

		sw_log("User login: redirecting to page: ".$this->opt['redirect_on_login'], PEAR_LOG_DEBUG);

		$this->controler->change_url_for_reload($this->opt['redirect_on_login']);
		return true;
	}
	
	/* this metod is called always at begining */
	function init(){
		parent::init();
		
		$this->controler->set_onload_js("
			if (document.forms['".$this->opt['form_name']."']['uname'].value != '') {
				document.forms['".$this->opt['form_name']."']['passw'].focus();
			} else {
				document.forms['".$this->opt['form_name']."']['uname'].focus();
			}
		");
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){

		if ($this->was_form_submited() or 
			(isset($_GET["redir_id"]) and $_GET["redir_id"] == $this->opt['instance_id'])){	// Is there data to process?

			$this->action=array('action'=>"login",
			                    'validate_form'=>true,
								'reload'=>true,
								'alone'=>true);
		}
		else $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false,
								 'alone'=>true);
	}
	
	/* create html form */
	function create_html_form(&$errors){
		global $lang_str;
		parent::create_html_form($errors);

		$cookie_uname="";
		if (isset($_COOKIE['serwebuser'])) $cookie_uname=$_COOKIE['serwebuser'];
		
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"uname",
									 "size"=>20,
									 "maxlength"=>50,
		                             "value"=>$cookie_uname,
									 "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_username'],
									 "extrahtml"=>"autocomplete='off' ".
									 	($this->opt['fully_qualified_name_on_login'] ? " onBlur='login_completion(this)'" : "")));
									 
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"passw",
		                             "value"=>"",
									 "size"=>20,
									 "maxlength"=>25,
									 "pass"=>1));
		
		$this->f->add_element(array("type"=>"checkbox",
		                             "name"=>"remember_uname",
		                             "value"=>"1",
									 "checked"=>$cookie_uname?1:0));

	}

	function check_admin_privilege($user, &$errors){
		global $data_auth;
		//check for admin privilege
		if (false === $privileges = $data_auth->get_privileges_of_user(
					$user,
					array('change_privileges','is_admin'),
					$errors)
			) return false;

		foreach($privileges as $row)
			if ($row->priv_name=='is_admin' and $row->priv_value) return true;
	
		return false;
	}


	/* validate html form */
	function validate_form(&$errors){
		global $config, $data, $data_auth, $lang_str;

		// don't display logout mesage in case that form was submited
		if (isset($_GET['logout'])) unset($_GET['logout']);

		if (isset($_GET["redir_id"]) and 
		    isModuleLoaded('xxl') and 
		    $this->opt['xxl_redirect_after_login']){
		    
				$this->username = $_GET['uname'];
				$this->domain   = $_GET['domain'];
				$this->password = $_GET['pass'];
		}
		else{
			if (false === parent::validate_form($errors)) return false;
	
			$this->password = $_POST['passw'];
	
	
			sw_log("User login: values from login form: username: ".
					$_POST['uname'].", password: ".$this->password, PEAR_LOG_DEBUG);
	
			//if fully quantified username is given
			if ($this->opt['fully_qualified_name_on_login']) {
				// parse username and domain from it
				if (ereg("^([^@]+)@(.+)", $_POST['uname'], $regs)){
					$this->username=$regs[1];
					$this->domain=$regs[2];
					
				}
				else {
					sw_log("User login: authentication failed: unsuported format of username. Can't parse username and domain part", PEAR_LOG_INFO);
					$errors[]=$lang_str['bad_username'];
	 				return false;
				}
			}
			else{
				$this->username=$_POST['uname'];
				$this->domain=$config->domain;
			}
		}

		sw_log("User login: checking password of user with username: ".
				$this->username.", domain: ".$this->domain, PEAR_LOG_DEBUG);
		

		$data_auth->set_xxl_user_id('sip:'.$this->username.'@'.$this->domain);
		$data_auth->expect_user_id_may_not_exists();


		if ($this->opt['check_supported_domain_on_login']){
			if (true !== $data_auth->domain_exists($this->domain, $errors)){
				sw_log("User login: authentication failed: domain '".$this->domain."'' is not supported. Please check table domain", PEAR_LOG_INFO);
				$errors[]=$lang_str['bad_username'];
				return false;
			}
		}


		if (false === $this->user_uuid = $data_auth->check_passw_of_user($this->username, $this->domain, $this->password, $errors)) {
			sw_log("User login: authentication failed: bad username or domain or password ", PEAR_LOG_INFO);
			$errors[]=$lang_str['bad_username'];
			return false;
		}

		if (is_null($this->user_uuid)){
			sw_log("User login: authentication failed: no user ID", PEAR_LOG_INFO);
			$errors[]=$lang_str['bad_username'];
			return false;
		}

		if ($this->opt['check_admin_privilege']){
			if (!$this->check_admin_privilege(
						new Cserweb_auth($this->user_uuid, $this->username, $this->domain), 
						$errors)){ 
				$errors[]=$lang_str['bad_username']; 
				sw_log("User login: authentication failed: user hasn't admin privileges", PEAR_LOG_INFO);
				return false;
			}
		}

		sw_log("User login: authentication succeeded, uuid: ".$this->user_uuid, PEAR_LOG_DEBUG);

		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['logout'])){
			$msgs[]=&$this->opt['msg_logout'];
			$this->smarty_action="was_logged_out";
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
					 'before'      => ($this->opt['fully_qualified_name_on_login'] ?
					 		'login_completion(f.uname);':
							''));
	}
}


?>

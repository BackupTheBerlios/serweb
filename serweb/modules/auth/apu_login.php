<?php
/**
 * Application unit login 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_login.php,v 1.5 2005/12/22 13:14:12 kozlik Exp $
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
 *	'auth_class'				(string) default: "Auth"
 *	 Name of class auth class which is used for validate credentials and which
 *	 is created after successfull authentication.
 *	
 *	'check_admin_privilege'		(bool) default: false
 *	 check if user has administrator privilege
 *	
 *	'fully_qualified_name_on_login'	(bool) default: $config->fully_qualified_name_on_login
 *	 true if should be entered fully qualifide username (username@realm)
 *	
 *	'redirect_on_login'			(string) default: 'my_account.php'
 *	 name of script ot which is browser redirected after succesfull login
 *	
 *	'cookie_domain'				(string) default: null
 *	 The domain that the cookie in which is stored username is available 
 *	
 *	'unset_lang_on_login'		(bool) default: true
 *	 Unset session variable containg language after successful log in
 *	 It allows to set language by user attribute
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
	var $uid = null;
	var $did = null;
	var $username = null;
	var $realm = null;
	var $password = null;
	var $perms = null;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array();
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array('login_completion.js.php');
	}
	
	/* constructor */
	function apu_login(){
		global $lang_str, $config;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['fully_qualified_name_on_login'] = $config->fully_qualified_name_on_login;

		$this->opt['redirect_on_login'] = 'my_account.php';
		
		$this->opt['check_admin_privilege'] = false;

		$this->opt['cookie_domain'] = null;

		$this->opt['xxl_redirect_after_login'] = false;

		$this->opt['auth_class'] = 'Auth';

		$this->opt['unset_lang_on_login'] = true;

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
										'src'  => get_path_to_buttons("btn_login.gif", $_SESSION['lang']));
		
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
	
	function action_login(&$errors){
		global $lang_str, $config;

		unset($_SESSION['auth']);

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
										"realm"    => $this->realm,
										"pass"     => $this->password,
										"redir_id" => $this->opt['instance_id'])));
		}

		$_SESSION['auth'] = new $this->opt['auth_class'];
		$_SESSION['auth'] -> authenticate_as($this->uid, $this->username, $this->realm);
		$_SESSION['auth'] -> set_did($this->did);

		if (is_array($this->perms))
			$_SESSION['auth']->set_perms($this->perms);

		sw_log("User login: redirecting to page: ".$this->opt['redirect_on_login'], PEAR_LOG_DEBUG);

		$this->controler->change_url_for_reload($this->opt['redirect_on_login']);
		if ($this->opt['unset_lang_on_login']) unset($_SESSION['lang']);
		return true;
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
								 'reload'=>false);
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


	/* validate html form */
	function validate_form(&$errors){
		global $config, $lang_str;

		// don't display logout mesage in case that form was submited
		if (isset($_GET['logout'])) unset($_GET['logout']);

		if (isset($_GET["redir_id"]) and 
		    isModuleLoaded('xxl') and 
		    $this->opt['xxl_redirect_after_login']){
		    
				$this->username = $_GET['uname'];
				$this->realm    = $_GET['realm'];
				$this->password = $_GET['pass'];
		}
		else{
			if (false === parent::validate_form($errors)) return false;
	
			$this->password = $_POST['passw'];
	
	
			sw_log("User login: values from login form: username: ".
					$_POST['uname'].", password: ".$this->password, PEAR_LOG_DEBUG);
	
			//if fully quantified username is given
			if ($this->opt['fully_qualified_name_on_login']) {
				// parse username and realm from it
				if (ereg("^([^@]+)@(.+)", $_POST['uname'], $regs)){
					$this->username=$regs[1];
					$this->realm=$regs[2];
					
				}
				else {
					sw_log("User login: authentication failed: unsuported format of username. Can't parse username and realm part", PEAR_LOG_INFO);
					$errors[]=$lang_str['bad_username'];
	 				return false;
				}
			}
			else{
				$this->username=$_POST['uname'];
				$this->realm = $config->domain;
			}
		}

		sw_log("User login: checking password of user with username: ".
				$this->username.", realm: ".$this->realm, PEAR_LOG_DEBUG);

		/* validate credentials */
		$uid = call_user_func_array(array($this->opt['auth_class'], 'validate_credentials'), 
		                            array($this->username, $this->realm, $this->password, array()));

		if (false === $uid) return false;

		/* find out domain id */
		$did = call_user_func_array(array($this->opt['auth_class'], 'find_out_did'), 
		                            array($this->username, $this->realm, $uid, array()));

		if (false === $did) return false;

		/* set_permissions */
		$perms = call_user_func_array(array($this->opt['auth_class'], 'find_out_perms'), 
		                              array($uid, array()));

		if (false === $perms) return false;

		if ($this->opt['check_admin_privilege']){
			if (!in_array('admin', $perms)){
				$errors[]=$lang_str['bad_username']; 
				sw_log("User login: authentication failed: user hasn't admin privileges", PEAR_LOG_INFO);
				return false;
			}
		}

		$this->uid = $uid;
		$this->did = $did;
		$this->perms = $perms;

		sw_log("User login: authentication succeeded, uid: ".$this->uid, PEAR_LOG_DEBUG);

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

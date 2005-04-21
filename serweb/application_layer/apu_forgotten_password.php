<?php
/**
 * Application unit forgotten_password
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_forgotten_password.php,v 1.1 2005/04/21 15:09:45 kozlik Exp $
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
 *	'mail_file'					(string) default: mail_register.txt
 *	 name of file contining text of mail with login informations which is send 
 *
 *	'login_script'				(string) default: my_account.php
 *	 name of script within user pages subdirecotry which accept precreated phplib session
 *	
 *	'msg_send'					default: $lang_str['msg_password_sended_s'] and $lang_str['msg_password_sended_l']
 *	 message which should be showed on on password succesfuly send - assoc array with keys 'short' and 'long'
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
 *	  'was_sended' - when user submited form and login info was successfuly sended
 *	
 */

class apu_forgotten_password extends apu_base_class{
	var $smarty_action='default';
	
	var $sip_user;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_sip_user');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_forgotten_password(){
		global $config, $lang_str, $sess_lang;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['domain'] =				$config->domain;
		$this->opt['mail_file'] =			"mail_forgot_password.txt";
		$this->opt['login_script'] =		"my_account.php"; 

		/* message on attributes update */
		$this->opt['msg_send']['short'] =	&$lang_str['msg_password_sended_s'];
		$this->opt['msg_send']['long']  =	&$lang_str['msg_password_sended_l'];
		
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

	function action_send(&$errors){
		global $config, $data, $sess_lang, $lang_str, $pre_uid, $pre_uid_expires;


		$pre_uid=$this->sip_user['uuid'];
		$pre_uid_expires=time()+$config->pre_uid_expires;

		$my_sess=new phplib_Session();
		$my_sess->set_container();
		$my_sess->name=$my_sess->classname;
		$my_sess->id = $my_sess->that->ac_newid(md5(uniqid($my_sess->magic)), $my_sess->name);
		$my_sess->register("pre_uid");
		$my_sess->register("pre_uid_expires");
		$my_sess->freeze();


		$mail = read_lang_txt_file($this->opt['mail_file'], "txt", $sess_lang, 
					array(array("domain", $this->opt['domain']),
					array("session_url", $config->root_uri.$config->user_pages_path.$this->opt['login_script']."?".$my_sess->name."=".$my_sess->id)));
					
		if ($mail === false){ 
			/* needn't write message to log. It's written by function read_lang_txt_file */
			$errors[]=$lang_str['err_sending_mail']; 
			return false;	
		}

		/* if subject isn't defined in txt file */
		if (!isset($mail['headers']['subject'])) $mail['headers']['subject'] = "";

		if (!send_mail($this->sip_user['email'], $mail['headers']['subject'], $mail['body'])){
			$errors[]=$lang_str['err_sending_mail']; 
			
			$this->controler->_form_load_defaults();
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
		if ($this->was_form_submited()){	// Is there data to process?
			$this->action=array('action'=>"send",
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
		                             "name"=>"uname",
									 "size"=>20,
									 "maxlength"=>64,
		                             "value"=>"",
									 "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_username'],
									 "extrahtml"=>"autocomplete='off'"));
	}

	/* validate html form */
	function validate_form(&$errors){
		global $config, $data;
		if (false === parent::validate_form($errors)) return false;

		if (false === $this->sip_user = $data->get_sip_user($_POST['uname'], $this->opt['domain'], $errors)) return false;

		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;

		if (isset($_GET['m_fp_pass_sended']) and $_GET['m_fp_pass_sended'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_send'];
			$this->smarty_action="was_sended";
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
					 'before'      => '');
	}
}

?>

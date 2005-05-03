<?
/**
 * Application unit registration
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_registration.php,v 1.6 2005/05/03 09:01:49 kozlik Exp $
 * @package   serweb
 */ 


/** 
 *	Application unit registration 
 *
 *
 *	This application unit is used for registration new users
 *	
 *	Configuration:
 *	--------------
 *	
 *	'domain'					(string) default: $config->domain
 *	 domain to which users will be registered
 *	 
 *	'mail_file'					(string) default: mail_register.txt
 *	 name of file contining text of mail which is send after successfull registration
 *	
 *	'terms_file					(string) default: terms.txt
 *	 name of file containing terms and conditions
 *
 *	'confirmation_script'		(string) default: reg/confirmation.php
 *	 name of script within user directory for confirmation of registration
 *	
 *	'msg_update'				default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
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
 *	'smarty_reg_adress'			name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_action']		(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'finished' - when user submited form and data was succefully stored
 *	
 *	opt['smarty_reg_adress']	(reg_sip_address)
 *	  contain sip uri of user who registered (avaiable only if smarty_action == finished)
 *
 *
 *	@package   serweb
 */

class apu_registration extends apu_base_class{
	var $smarty_action='default';
	var $terms = "";

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		global $config;

		$req = array('get_time_zones',  'is_user_exists', 
			'add_user_to_subscriber');
		
		if ($config->enable_XXL){
			$req = array_merge($req, array('get_proxy_for_new_user_xxl',
				'set_proxy_xxl'));
		}

		return $req;
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_registration(){
		global $lang_str, $config, $sess_lang;
		parent::apu_base_class();

		/* set default values to $this->opt */		

		$this->opt['domain'] =				$config->domain;
		$this->opt['mail_file'] =			"mail_register.txt";
		$this->opt['terms_file'] =			"terms.txt";
		$this->opt['confirmation_script'] =	"reg/confirmation.php";

 		
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
		/* registered sip address */
		$this->opt['smarty_reg_adress'] = 	'reg_sip_address';
		
		$this->opt['form_submit']=array('type' => 'image',
										'text' => $lang_str['b_register'],
										'src'  => get_path_to_buttons("btn_register.gif", $sess_lang));
		
	}

	function action_register(&$errors){
		global $config, $data, $lang_str, $sess_lang;

		$uuid = md5(uniqid($_SERVER["SERVER_ADDR"]));

		if ($config->enable_XXL){
			if (false === $proxy = $data->get_proxy_for_new_user_xxl(
						new Cserweb_auth($uuid, $_POST['uname'], $this->opt['domain']),
						array ("set_proxy" => true),
						$errors))
				return false;

			if (false === $data->set_proxy_xxl(
						new Cserweb_auth($uuid, $_POST['uname'], $this->opt['domain']), 
						$proxy['proxy'], 
						null, 
						$errors)) 
				return false;
		}

		
		$confirm=md5(uniqid(rand()));

		if (!$data->add_user_to_subscriber(
				array("uuid" => $uuid, 
				      "uname" => $_POST['uname'], 
				      "domain" => $this->opt['domain'], 
					  "password" => $_POST['passwd'], 
					  "fname" => $_POST['fname'], 
					  "lname" => $_POST['lname'], 
					  "phone" => $_POST['phone'], 
					  "email" => $_POST['email'], 
					  "timezone" => $_POST['timezone'],
					  "confirm" => $confirm),
				array("pending" => true), 
				$errors)) 
			return false;


		$sip_address="sip:".$_POST['uname']."@".$this->opt['domain'];
		$confirmation_url = $config->root_uri.
							$config->user_pages_path.
							$this->opt['confirmation_script'].
							"?nr=".$confirm.
							($config->enable_XXL ? 
								"&pr=".RawURLEncode(base64_encode($proxy['proxy'])):
								"");

		$mail = read_lang_txt_file($this->opt['mail_file'], "txt", $sess_lang, 
					array(array("domain", $this->opt['domain']),
					      array("sip_address", $sip_address),
						  array("confirmation_url", $confirmation_url)));
					

		if ($mail === false){ 
			/* needn't write message to log. It's written by function read_lang_txt_file */
			$errors[]=$lang_str['err_sending_mail']; 
			return false;	
		}

		/* if subject isn't defined in txt file */
		if (!isset($mail['headers']['subject'])) $mail['headers']['subject'] = "";

		if (!send_mail($_POST['email'], $mail['headers']['subject'], $mail['body'])){
			$errors[]=$lang_str['err_sending_mail']; 
			
			$this->controler->_form_load_defaults();
			return false;
		}

		return array("reg_fi_sip_adr=".RawURLEncode($sip_address),
		             "reg_finish=".RawURLEncode($this->opt['instance_id']));
	}

	function action_finish(&$errors){
		$this->smarty_action="finished";
	}

	
	/* this metod is called always at begining */
	function init(){
		global $sess_lang, $config;
		parent::init();

		$this->reg = new Creg;				// create regular expressions class

		/* read txt files */
		$t = read_lang_txt_file($this->opt['terms_file'], "txt", $sess_lang, array(array("domain", $this->opt['domain'])));
		if ($t !== false){
 			$this->terms = $t['body'];
		}
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			$this->action=array('action'=>"register",
			                    'validate_form'=>true,
								'reload'=>true);
		}
		elseif(isset($_GET['reg_finish']) and $_GET['reg_finish'] == $this->opt['instance_id']){
			$this->action=array('action'=>"finish",
			                    'validate_form'=>false,
								'reload'=>false);
		}
		else $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
	}

	/* create html form */
	function create_html_form(&$errors){
		global $lang_str, $data;
		parent::create_html_form($errors);

		$opt=$data->get_time_zones($errors);
		$options[]=array("label"=>$lang_str['choose_timezone'],"value"=>"");
		foreach ($opt as $v) $options[]=array("label"=>$v,"value"=>$v);

		$this->f->add_element(array("type"=>"select",
									 "name"=>"timezone",
									 "options"=>$options,
									 "size"=>1,
		                             "valid_e"=>$lang_str['fe_not_choosed_timezone'],
									 "extrahtml"=>"style='width:250px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"uname",
									 "size"=>23,
									 "maxlength"=>50,
		                             "value"=>"",
									 "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_username'],
		                             "valid_regex"=>$this->reg->serweb_username,
		                             "valid_e"=>$lang_str['fe_uname_not_follow_conventions'],
									 "extrahtml"=>"autocomplete'off' style='width:250px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"passwd",
		                             "value"=>"",
									 "size"=>23,
									 "maxlength"=>25,
									 "pass"=>1,
									 "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_password'],
									 "extrahtml"=>"style='width:250px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"passwd_r",
		                             "value"=>"",
									 "size"=>23,
									 "maxlength"=>25,
									 "pass"=>1,
									 "extrahtml"=>"style='width:250px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"fname",
									 "size"=>23,
									 "maxlength"=>25,
		                             "value"=>"",
									 "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_your_fname'],
									 "extrahtml"=>"style='width:250px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"lname",
									 "size"=>23,
									 "maxlength"=>45,
		                             "value"=>"",
									 "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_your_lname'],
									 "extrahtml"=>"style='width:250px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"email",
									 "size"=>23,
									 "maxlength"=>50,
		                             "value"=>"",
		                             "valid_regex"=>$this->reg->email,
		                             "valid_e"=>$lang_str['fe_not_valid_email'],
									 "extrahtml"=>"style='width:250px;'"));
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"phone",
									 "size"=>23,
									 "maxlength"=>15,
		                             "value"=>"",
									 "extrahtml"=>"style='width:250px;'"));
		$this->f->add_element(array("type"=>"textarea",
		                             "name"=>"terms",
		                             "value"=>$this->terms,
									 "rows"=>8,
									 "cols"=>38,
		                             "wrap"=>"soft",
									 "extrahtml"=>"style='width:415px;'"));
		$this->f->add_element(array("type"=>"checkbox",
		                             "name"=>"accept",
		                             "value"=>1,
									 "extrahtml"=>"style=''"));
		
	}

	/* validate html form */
	function validate_form(&$errors){
		global $lang_str, $data;
		if (false === parent::validate_form($errors)) return false;

		if ($_POST['passwd'] and ($_POST['passwd'] != $_POST['passwd_r'])){
			$errors[]=$lang_str['fe_passwords_not_match']; return false;
		}

		if (!isset($_POST['accept']) or !$_POST['accept']){
			$errors[]=$lang_str['fe_not_accepted_terms']; return false;
		}

		if (0 > $user_exists=$data->is_user_exists($_POST['uname'], $this->opt['domain'], $errors)) return false;

		if ($user_exists) {
			$errors[]=$lang_str['fe_uname_already_choosen_1']." '".$_POST['uname']."' ".$lang_str['fe_uname_already_choosen_2']; 
			return false;
		}
		
		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
/*		if (isset($_GET['m_my_apu_updated']) and $_GET['m_my_apu_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}
 */
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		
		if ($this->smarty_action == "finished")
			$smarty->assign_by_ref($this->opt['smarty_reg_adress'], $_GET['reg_fi_sip_adr']);
		
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		global $lang_str;

		$js_after = "if (f.passwd.value!=f.passwd_r.value){
						alert('".addslashes($lang_str['fe_passwords_not_match'])."');
						f.passwd.focus();
						return (false);
					}

					if (!f.accept.checked){
						alert('".addslashes($lang_str['fe_not_accepted_terms'])."');
						f.accept.focus();
						return (false);
					}";


		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => $js_after,
					 'before'      => '');
	}
}

?>

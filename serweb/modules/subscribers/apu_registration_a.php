<?
/**
 * Application unit registration by administrator
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_registration_a.php,v 1.3 2005/11/04 13:25:37 kozlik Exp $
 * @package   serweb
 */ 


/** 
 *	Application unit registration by administrator
 *
 *
 *	This application unit is used for registration new users
 *	
 *	Configuration:
 *	--------------
 *	
 *	'mail_file'					(string) default: mail_registered_by_admin.txt
 *	 name of file contining text of mail which is send after successfull registration
 *	
 *	'create_numeric_alias'		(bool) default: $config->create_numeric_alias_to_new_users
 *	 If true, create numeric alias for new subscriber
 *	
 *	'add_to_aliases_table_too'	(bool) default: $config->copy_new_subscribers_to_aliases_table
 *	 If true, serweb will add new subscriber also into aliases table instead of into subscriber table only
 *	
 *	'allowed_domains'			(array)	default: null
 *	 array of domain names from which may admin select. If is not set text field
 *	 is displayed instead of select
 *	 
 *	'pre_selected_domain'		(string)	default: null
 *	 name of domain which is preselected
 *	 
 *	'redirect_on_register'		(string) default: ''
 *	 name of script to which is browser redirected after succesfull registration of new user
 *	 if empty, browser isn't redirected
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

class apu_registration_a extends apu_base_class{
	var $smarty_action='default';

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		global $config;

		return array('get_time_zones',  'is_user_exists', 
			'add_user_to_subscriber', 'add_new_alias', 'get_new_alias_number',
			'delete_alias', 'del_user_from_subscriber', 'get_domains');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_registration_a(){
		global $lang_str, $config, $sess_lang;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['allowed_domains'] = null;
		$this->opt['pre_selected_domain'] = null;
		
		$this->opt['mail_file'] =	'mail_registered_by_admin.txt';
		$this->opt['login_script'] =	'';
		$this->opt['redirect_on_register'] = "";

		/* alias generation */
		$this->opt['create_numeric_alias'] =		$config->create_numeric_alias_to_new_users;
		$this->opt['add_to_aliases_table_too'] =	$config->copy_new_subscribers_to_aliases_table;

 		
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
			
	}

	function action_register(&$errors){
		global $config, $data, $lang_str, $sess_lang;

		$uuid = md5(uniqid($_SERVER["SERVER_ADDR"]));

		/* generate new password */
		$password = substr(md5(uniqid('')), 0, 5);
		
		if (!$data->add_user_to_subscriber(
				array("uuid" => $uuid, 
				      "uname" => $_POST['uname'], 
				      "domain" => $_POST['domain'], 
					  "password" => $password, 
					  "fname" => $_POST['fname'], 
					  "lname" => $_POST['lname'], 
					  "phone" => $_POST['phone'], 
					  "email" => $_POST['email'], 
					  "timezone" => $_POST['timezone'],
					  "confirmation" => $uuid),
				array("pending" => false), 
				$errors)) 
			return false;

		$user_param = user_to_get_param($uuid, $_POST['uname'], $_POST['domain'], "u");

		$remove_from_subscriber=true;
		$remove_alias=false;
		do{
			if ($this->opt['add_to_aliases_table_too']){
				if (false === $data->add_new_alias($user_id, $user_id->uname, $user_id->domain, $errors)) break;
				$remove_alias=true;
			}

			if ($this->opt['create_numeric_alias']){
				// generate alias number 
				if (false === $alias=$data->get_new_alias_number($user_id->domain, $errors)) break;
		
				// add alias to fifo
				if (false === $data->add_new_alias($user_id, $alias, $user_id->domain, $errors)) break;
			}
			$remove_from_subscriber=false;
			$remove_alias=false;
		}while (false);
			
		// some error occured during account creating, we should remove user from table subscriber
		if ($remove_from_subscriber or $remove_alias){
			if ($remove_from_subscriber) $data->del_user_from_subscriber($uuid, $errors);
			if ($remove_alias) $data->delete_alias($user_id, $user_id->uname, $user_id->domain, $errors);
			return false;
		}

		$sip_address="sip:".$_POST['uname']."@".$_POST['domain'];
		$login_url = $config->root_uri.
					 $config->user_pages_path.
					 $this->opt['login_script'];
		$username = $config->fully_qualified_name_on_login ? 
		              ($_POST['uname']."@".$_POST['domain']) : 
		               $_POST['uname'];

		$mail = read_lang_txt_file($this->opt['mail_file'], "txt", $sess_lang, 
					array(array("domain", $_POST['domain']),
					      array("sip_address", $sip_address),
						  array("login_url", $login_url),
						  array("username", $username),
						  array("password", $password),
						  array("email", $_POST['email']),
						  array("first_name", $_POST['fname']),
						  array("last_name", $_POST['lname'])));

		if ($mail === false){ 
			/* needn't write message to log. It's written by function read_lang_txt_file */
			$errors[]=$lang_str['err_sending_mail']; 
			return false;	
		}

		if (!send_mail($_POST['email'], $mail['body'], $mail['headers'])){
			$errors[]=$lang_str['err_sending_mail']; 
			
			$this->controler->_form_load_defaults();
			return false;
		}

		if ($this->opt['redirect_on_register'])
			$this->controler->change_url_for_reload($this->opt['redirect_on_register']);

		return array("m_user_registered=".RawURLEncode($this->opt['instance_id']),
		             $user_param);
	}

	function action_finish(&$errors){
		$this->smarty_action="finished";
	}

	
	/* this metod is called always at begining */
	function init(){
		global $sess_lang, $config;
		parent::init();

		$this->reg = Creg::singleton();		// get reference to regular expressions class
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
		global $lang_str, $data, $serweb_auth;
		parent::create_html_form($errors);

		if (is_array($this->opt['allowed_domains'])){

			$dom_options = array();
			foreach ($this->opt['allowed_domains'] as $v) 
				$dom_options[]=array("label"=>$v, "value"=>$v);

			$this->f->add_element(array("type"=>"select",
										 "name"=>"domain",
										 "options"=>$dom_options,
										 "value"=>($this->opt['pre_selected_domain'] ? $this->opt['pre_selected_domain'] : ""),
										 "size"=>1));
		}
		else{
			$this->f->add_element(array("type"=>"text",
										 "name"=>"domain",
										 "size"=>23,
										 "maxlength"=>128,
			                             "value"=>$serweb_auth->domain,
										 "minlength"=>1,
										 "length_e"=>$lang_str['fe_domain_not_selected']));
		}


		$timezones=$data->get_time_zones($errors);
		$tz_options[]=array("label"=>$lang_str['choose_timezone_of_user'],"value"=>"");
		foreach ($timezones as $v) $tz_options[]=array("label"=>$v,"value"=>$v);

		$this->f->add_element(array("type"=>"select",
									 "name"=>"timezone",
									 "options"=>$tz_options,
									 "size"=>1,
		                             "valid_e"=>$lang_str['fe_not_choosed_timezone']));
		                             
		                             
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"uname",
									 "size"=>23,
									 "maxlength"=>50,
		                             "value"=>"",
									 "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_username'],
		                             "valid_regex"=>$this->reg->serweb_username,
		                             "valid_e"=>$lang_str['fe_uname_not_follow_conventions'],
									 "extrahtml"=>"autocomplete'off'"));
									 
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"fname",
									 "size"=>23,
									 "maxlength"=>25,
		                             "value"=>""));
									 
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"lname",
									 "size"=>23,
									 "maxlength"=>45,
		                             "value"=>""));
									 
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"email",
									 "size"=>23,
									 "maxlength"=>50,
		                             "value"=>"",
		                             "valid_regex"=>$this->reg->email,
		                             "valid_e"=>$lang_str['fe_not_valid_email']));
		                             
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"phone",
									 "size"=>23,
									 "maxlength"=>15,
		                             "value"=>""));
	}

	/* validate html form */
	function validate_form(&$errors){
		global $lang_str, $data;
		if (false === parent::validate_form($errors)) return false;

		if (!$_POST['domain']){
			$errors[]=$lang_str['fe_domain_not_selected']; 
			return false;
		}

		if (!is_null($this->opt['allowed_domains'])){
			if (!in_array($_POST['domain'], $this->opt['allowed_domains'])){
				$errors[] = "You haven't access to domain which you selected: ".$_POST['domain']; 
				return false;
			}
		}


		if (0 > $user_exists=$data->is_user_exists($_POST['uname'], $_POST['domain'], $errors)) return false;

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
		
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		global $lang_str;

		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => '',
					 'before'      => '');
	}
}

?>

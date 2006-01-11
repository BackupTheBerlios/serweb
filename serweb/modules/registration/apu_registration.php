<?
/**
 * Application unit registration by administrator
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_registration.php,v 1.4 2006/01/11 15:16:50 kozlik Exp $
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
 *	 Name of file containing text of mail which is send after successfull registration.
 *	
 *	'mail_file_conf'			(string) default: null
 *	 Name of file continaing text of mail which is send after successfull registration.
 *	 If 'mail_file_conf' is set, 'mail_file_conf' is send when confirmation of 
 *	 registration is required and 'mail_file' is send when confirmation is not
 *	 required. Otherwise (if 'mail_file_conf' is not set) 'mail_file' is always
 *	 send.
 *	
 *	'create_numeric_alias'		(bool) default: $config->create_numeric_alias_to_new_users
 *	 If true, create numeric alias for new subscriber
 *	
 *	'register_in_domain'		(string)	default: null
 *	
 *	
 *	
 *	
 *	'require_confirmation'		(bool)		default: null
 *	 Require confirmation of registration. This option override attribute 
 *	 'require_conf'.
 *	
 *	'choose_passw'				(bool)		default: true
 *	
 *	
 *	
 *	'set_lang_attr'				(string)	default: null
 *	 Set the 'lang' attribute of registering user by the given lang
 *	
 *	'terms_file					(string) default: terms.txt
 *	 name of file containing terms and conditions
 *
 *	
 *	'allowed_domains'			(array)	default: null
 *	 array of domain IDs from which may admin select. If is not set all domains
 *	 in system are offered
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
 *	'smarty_attributes'			name of smarty variable - see below
 *	'smarty_req_conf'			name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_attributes'] 	(attributes)	
 *	  associative array containing info about attributes
 *	  keys: 
 *		'att_name' - name of attribute
 *		'att_desc' - human readable name of attribute
 *	
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
 *	opt['smarty_req_conf']		(require_confirmation)
 *	  Contain true if confirmation of registration is required.
 *	  (avaiable only if smarty_action == finished)
 *
 *	@package   serweb
 */

class apu_registration extends apu_base_class{
	var $smarty_action='default';
	var $domain_names;
	var $attr_types;
	var $js_after="";

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		global $config;

		return array('is_user_exists', 'add_credentials', 'add_uri',
		             'get_new_alias_number');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_registration(){
		global $lang_str, $config;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['register_in_domain'] = null;
		
		$this->opt['allowed_domains'] = null;
		$this->opt['pre_selected_domain'] = null;

		$this->opt['require_confirmation'] = null;
		$this->opt['choose_passw'] = true;

		$this->opt['terms_file'] =	null;

		
		$this->opt['mail_file'] =	'mail_registered_by_admin.txt';
		$this->opt['mail_file_conf'] =	null;
		$this->opt['login_script'] =	'';
		$this->opt['redirect_on_register'] = "";
		$this->opt['confirmation_script'] =	"";

		$this->opt['set_lang_attr']	= null;

		/* alias generation */
		$this->opt['create_numeric_alias'] =		$config->create_numeric_alias_to_new_users;

 		
		/* message on attributes update */
		$this->opt['msg_update']['short'] =	&$lang_str['msg_changes_saved_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_changes_saved_l'];
		
		/*** names of variables assigned to smarty ***/
		/* array containing names of attributes */
		$this->opt['smarty_attributes'] =	'attributes';
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';
		/* registered sip address */
		$this->opt['smarty_reg_adress'] = 	'reg_sip_address';

		$this->opt['smarty_req_conf'] = 	'require_confirmation';			
	}

	/* this metod is called always at begining */
	function init(){
		parent::init();

		$this->reg = Creg::singleton();		// get reference to regular expressions class
	}

	function action_register(&$errors){
		global $config, $data, $lang_str;

		$an = &$config->attr_names;

		/* generate confirmation string */
		$confirm=md5(uniqid(rand()));

		/* obtain password */		
		if ($this->opt['choose_passw']){
			$password = $_POST['passwd'];
		}
		else{
			/* generate new password */
			$password = substr(md5(uniqid('')), 0, 5);
		}

		/* obtain did */
		$did = is_null($this->opt['register_in_domain']) ? 
		           $_POST['domain'] :
		           $this->opt['register_in_domain'];

		/* set value of option 'require_confirmation' */
		if (is_null($this->opt['require_confirmation'])){
			$o = array('did' => $did);
			if (false === $this->opt['require_confirmation'] = 
					Attributes::get_attribute($an['require_conf'], $o)) return false;
		}


		/* get domain name */
		$domains = &Domains::singleton();
		if (false === $domain_name = $domains->get_domain_name($did)) return false;

		
		/* get realm */
		$da = &Domain_Attrs::singleton($did);
		if (false === $realm = &$da->get_attribute($an['digest_realm'])) return false;
		if (is_null($realm)) $realm = $domain_name;
		
		/* generate uid */
		$uid = $_POST['uname'].'@'.$realm;

		$user_param = user_to_get_param($uid, $_POST['uname'], $realm, "u");


		if (false === $data->transaction_start()) return false;

		/* store credentials */
		$o = array('disabled' => $this->opt['require_confirmation']);
		if (false === $data->add_credentials($uid, $_POST['uname'], $realm, $password, $o)) {
			$data->transaction_rollback();
			return false;
		}

		/* store uri */
		$o = array('disabled' => $this->opt['require_confirmation'],
		           'canon' => true);
		if (false === $data->add_uri($uid, $_POST['uname'], $did, $o)) {
			$data->transaction_rollback();
			return false;
		}

		/* store attributes */
		$ua = &User_Attrs::singleton($uid);
		foreach($this->attributes as $att){
			if (false === $ua->set_attribute($att, $_POST[$att])) {
				$data->transaction_rollback();
				return false;
			}
		}

		if (!is_null($this->opt['set_lang_attr'])){
			$u_lang = $this->opt['set_lang_attr'];

			/* get the attr_type of the lang attribute */
			$at_handler = &Attr_types::singleton();
			if (false === $lang_type = $at_handler->get_attr_type($an['lang'])) {
				$data->transaction_rollback();
				return false;
			}
			if (is_null($lang_type)) {
				ErrorHandler::add_error("Type of attribute 'lang' doesn't exists"); 
				$data->transaction_rollback();
				return false;
			}
			
			/* format the value */
			$lang_type->check_value($u_lang);
		
			/* store lang into DB */
			if (false === $ua->set_attribute($an['lang'], $u_lang)) {
				$data->transaction_rollback();
				return false;
			}
			
		}

		if ($this->opt['require_confirmation']){
			if (false === $ua->set_attribute($an['confirmation'], $confirm)) {
				$data->transaction_rollback();
				return false;
			}

			if (false === $ua->set_attribute($an['pending_ts'], time())) {
				$data->transaction_rollback();
				return false;
			}
		}

		if ($this->opt['create_numeric_alias']){
			// generate alias number 
			if (false === $alias=$data->get_new_alias_number($did, null)) {
				$data->transaction_rollback();
				return false;
			}
	
			/* store alias to URI table */
			$o = array('disabled' => $this->opt['require_confirmation'],
			           'canon' => false);
			if (false === $data->add_uri($uid, $alias, $did, $o)) {
				$data->transaction_rollback();
				return false;
			}
		}
			


		$sip_address="sip:".$_POST['uname']."@".$domain_name;
		$login_url = $config->root_uri.
					 $config->user_pages_path.
					 $this->opt['login_script'];

		$username = $config->fully_qualified_name_on_login ? 
		              ($_POST['uname']."@".$realm) : 
		               $_POST['uname'];

		$confirmation_url = $config->root_uri.
							$config->user_pages_path.
							$this->opt['confirmation_script'].
							"?nr=".$confirm.
							(isModuleLoaded('xxl') ? 
								"&pr=".RawURLEncode(base64_encode($proxy['proxy'])):
								"");

		if (is_null($this->opt['mail_file_conf'])) 
			$this->opt['mail_file_conf'] = $this->opt['mail_file'];

		if ($this->opt['require_confirmation'])	$mail_file = $this->opt['mail_file_conf'];
		else $mail_file = $this->opt['mail_file'];

		$mail = read_lang_txt_file($mail_file, "txt", $_SESSION['lang'], 
					array(array("domain", $domain_name),
					      array("sip_address", $sip_address),
						  array("login_url", $login_url),
						  array("confirmation_url", $confirmation_url),
						  array("username", $username),
						  array("password", $password),
						  array("email", isset($_POST[$an['email']]) ? $_POST[$an['email']] : ""),
						  array("first_name", isset($_POST[$an['fname']]) ? $_POST[$an['fname']] : ""),
						  array("last_name", isset($_POST[$an['lname']]) ? $_POST[$an['lname']] : "")));

		if ($mail === false){ 
			/* needn't write message to log. It's written by function read_lang_txt_file */
			$errors[]=$lang_str['err_sending_mail']; 
			$data->transaction_rollback();
			return false;	
		}


		if (!send_mail($_POST[$an['email']], $mail['body'], $mail['headers'])){
			$errors[]=$lang_str['err_sending_mail']; 
			
			$this->controler->_form_load_defaults();
			$data->transaction_rollback();
			return false;
		}

		if (false === $data->transaction_commit()) return false;

		if ($this->opt['redirect_on_register'])
			$this->controler->change_url_for_reload($this->opt['redirect_on_register']);

		return array("m_user_registered=".RawURLEncode($this->opt['instance_id']),
		             "reg_sip_adr=".RawURLEncode($sip_address),
		             "require_conf=".RawURLEncode($this->opt['require_confirmation']),
		             $user_param);
	}

	function action_finish(&$errors){
		$this->smarty_action="finished";
	}

	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			$this->action=array('action'=>"register",
			                    'validate_form'=>true,
								'reload'=>true);
		}
		elseif(isset($_GET['m_user_registered']) and $_GET['m_user_registered'] == $this->opt['instance_id']){
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
		global $lang_str, $data, $config;
		parent::create_html_form($errors);

		if (is_null($this->opt['register_in_domain']) and 
			false === $this->add_domain_to_form()) return false;		


		if (false === $this->add_attrs_to_form()) return false;


		if ($this->opt['terms_file']){
			/* read txt files */
			$t = read_lang_txt_file($this->opt['terms_file'], "txt", $_SESSION['lang'], array(array("domain", $config->domain)));
			if ($t !== false){
	 			$terms = $t['body'];
			}

			$this->f->add_element(array("type"=>"textarea",
			                             "name"=>"terms",
			                             "value"=>$terms,
										 "rows"=>8,
										 "cols"=>38,
			                             "wrap"=>"soft"));

			$this->f->add_element(array("type"=>"checkbox",
			                             "name"=>"accept",
			                             "value"=>1));
			                             
			$this->js_after .= "
						if (!f.accept.checked){
							alert('".addslashes($lang_str['fe_not_accepted_terms'])."');
							f.accept.focus();
							return (false);
						}";
		}


		                             
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

		if ($this->opt['choose_passw']){
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"passwd",
			                             "value"=>"",
										 "size"=>23,
										 "maxlength"=>25,
										 "pass"=>1,
										 "minlength"=>1,
										 "length_e"=>$lang_str['fe_not_filled_password']));
										 
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"passwd_r",
			                             "value"=>"",
										 "size"=>23,
										 "maxlength"=>25,
										 "pass"=>1));

			$this->js_after .= "
						if (f.passwd.value!=f.passwd_r.value){
							alert('".addslashes($lang_str['fe_passwords_not_match'])."');
							f.passwd.focus();
							return (false);
						}";
		}
									 
	}


	function add_domain_to_form(){
		$domains = &Domains::singleton();
		if (false === $this->domain_names = $domains->get_id_name_pairs()) return false;

		if (is_array($this->opt['allowed_domains'])){
			$dom_tmp = array();			
			foreach ($this->opt['allowed_domains'] as $v){
				$dom_tmp[$v] = $this->domain_names[$v];
			}
			$this->domain_names = &$dom_tmp;
		}

		$dom_options = array();
		foreach ($this->domain_names as $k => $v) 
			$dom_options[]=array("label"=>$v, "value"=>$k);
	

		$this->f->add_element(array("type"=>"select",
									 "name"=>"domain",
									 "options"=>$dom_options,
									 "value"=>($this->opt['pre_selected_domain'] ? $this->opt['pre_selected_domain'] : ""),
									 "size"=>1));
	
		return true;
	}



	function add_attrs_to_form(){

		$attr_types = &Attr_types::singleton();
	
		//get list of attributes
		if (false === $this->attr_types = &$attr_types->get_attr_types()) return false;

		$this->attributes = array();
		foreach($this->attr_types as $k => $v){
			if ($v->fill_on_register()) $this->attributes[] = $k;
		}

		if (!is_null($this->opt['register_in_domain'])){
			// get domain_attrs
			$da = &Domain_Attrs::singleton($this->opt['register_in_domain']);
			if (false === $domain_attrs = &$da->get_attributes()) return false;
		}
		
		// get global_attrs
		$ga = &Global_Attrs::singleton();
		if (false === $global_attrs = &$ga->get_attributes()) return false;
		
		
		$this->attr_values = array();
		foreach($this->attributes as $v){
			if (isset($domain_attrs[$v])){
				$this->attr_values[$v] = $domain_attrs[$v];
			}		
			elseif (isset($global_attrs[$v])){
				$this->attr_values[$v] = $global_attrs[$v];
			}
			else {
				// If the value of attribute is not found, set it as null
				$this->attr_values[$v] = null;
			}
		}
		

		// add elements to form object
		foreach($this->attributes as $v){
			$opt = array();

			$this->attr_types[$v]->form_element($this->f, 
			                                    $this->attr_values[$v],
			                                    $opt);
		}
		
		return true;	
	}





	/* validate html form */
	function validate_form(&$errors){
		global $lang_str, $data, $config;
		if (false === parent::validate_form($errors)) return false;

		$an = &$config->attr_names;


		if (is_null($this->opt['register_in_domain'])){
			if (!isset($_POST['domain']) or $_POST['domain']===""){
				$errors[]=$lang_str['fe_domain_not_selected']; 
				return false;
			}

			$did = $_POST['domain'];
			if (!isset($this->domain_names[$did])){
				$d = &Domains::singleton();
				$errors[] = "You haven't access to domain which you selected: ".$d->get_domain_name($did); 
				return false;
			}
		}
		else{
			$did = $this->opt['register_in_domain'];
		}

		if (0 === $user_exists = $data->is_user_exists($_POST['uname'], $did)) return false;

		if ($user_exists < 0) {
			$errors[]=$lang_str['fe_uname_already_choosen_1']." '".$_POST['uname']."' ".$lang_str['fe_uname_already_choosen_2']; 
			return false;
		}

		if ($this->opt['choose_passw'] and ($_POST['passwd'] != $_POST['passwd_r'])){
			$errors[]=$lang_str['fe_passwords_not_match']; return false;
		}

		if ($this->opt['terms_file'] and empty($_POST['accept'])){
			$errors[]=$lang_str['fe_not_accepted_terms']; return false;
		}

		//check values of attributes
		foreach($this->attributes as $att){
			if (!$this->attr_types[$att]->check_value($_POST[$att])){
				$errors[]=$lang_str['fe_invalid_value_of_attribute']." ".$this->attr_types[$att]->get_description(); 
				return false;
			}
		}

		if (!$_POST[$an['email']]){
			$errors[]=$lang_str['fe_not_valid_email']; 
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


	/**
	 *	returned two dimensional array of attributes
	 *	it is ordered array which elements contain associative array, with keys:
	 *		'att_name'
	 *		'att_desc'
	 *		'att_type'
	 *		'att_spec' (for type 'radio' only)
	 *	
	 *	this method also return javascript on form submit for attributes which type is sip address
	 *	
	 *	@access private
	 */
	function format_attributes_for_output(){
	
		$out=array();
		foreach($this->attributes as $att){

			if ($this->attr_types[$att]->get_type() == "sip_adr")		
					$this->js_on_subm.="sip_address_completion(f.".$att.");";

			$out[$att]['att_desc'] = $this->attr_types[$att]->get_description();
			$out[$att]['att_name'] = $att;
			$out[$att]['att_type'] = $this->attr_types[$att]->get_type();
			
			/* if type of attribute is radio, create list of options
			 * ass array of asociative arrays with entries 'label' and 'value'
			 */
			if ($this->attr_types[$att]->get_type() == "radio") {
				foreach($this->f->elements[$att]['ob']->options as $v){
					$out[$att]['att_spec'][] = $v;
				}
			}
		}
		return $out;
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign($this->opt['smarty_attributes'], 
		                $this->format_attributes_for_output());

		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);

		if ($this->smarty_action == "finished"){
			$smarty->assign_by_ref($this->opt['smarty_reg_adress'], $_GET['reg_sip_adr']);
			$smarty->assign_by_ref($this->opt['smarty_req_conf'], $_GET['require_conf']);
		}
		
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		global $lang_str;

		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => $this->js_after,
					 'before'      => '');
	}
}

?>

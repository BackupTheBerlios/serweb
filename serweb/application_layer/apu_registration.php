<?

/*
 * $Id: apu_registration.php,v 1.2 2005/01/30 20:56:38 kozlik Exp $
 */ 

/* Application unit registration */

/*
   This application unit is used for registration new users
   
   Configuration:
   --------------

   'domain'					(string) default: $config->domain
     domain to which users will be registered
	 
   'mail_body'				(string) default: $config->mail_register
     body of mail which is send after successfull registration
   
   'mail_subject'			(string) default: $config->register_subj
     subject of mail which is send after successfull registration
   
   'terms'					(string) default: $config->terms_and_conditions
     terms and conditions
   
   'msg_update'					default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
     message which should be showed on attributes update - assoc array with keys 'short' and 'long'
   								
   'form_name'					(string) default: ''
     name of html form
   
   'form_submit'				(assoc)
     assotiative array describe submit element of form. For details see description 
	 of method add_submit in class form_ext

   'smarty_form'				name of smarty variable - see below
   'smarty_action'				name of smarty variable - see below
   'smarty_reg_adress'
   
   Exported smarty variables:
   --------------------------
   opt['smarty_form'] 			(form)			
     phplib html form
	 
   opt['smarty_action']			(action)
	  tells what should smarty display. Values:
   	  'default' - 
	  'finished' - when user submited form and data was succefully stored

   opt['smarty_reg_adress']		(reg_sip_address)
      contain sip uri of user who registered (avaiable only if smarty_action == finished)
*/

class apu_registration extends apu_base_class{
	var $smarty_action='default';

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_time_zones',  'is_user_exists', 'add_user_to_subscriber');
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

		$this->opt['domain'] =			$config->domain;
		$this->opt['mail_body'] =		$config->mail_register;
		$this->opt['mail_subject'] =	$config->register_subj;
		$this->opt['terms'] =			$config->terms_and_conditions;
		
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
										'text' => 'save',
										'src'  => $config->img_src_path."buttons/btn_submit.gif");
		
	}

	function action_register(&$errors){
		global $config, $data, $lang_str;
		
		$confirm=md5(uniqid(rand()));

		if (!$data->add_user_to_subscriber($_POST['uname'], $this->opt['domain'], $_POST['passwd'], $_POST['fname'], 
											$_POST['lname'], $_POST['phone'], $_POST['email'], $_POST['timezone'], 
											$confirm, $config->data_sql->table_pending, $errors)) return false;

		$sip_address="sip:".$_POST['uname']."@".$this->opt['domain'];

		$mail_body=str_replace("#confirm#", $confirm, $this->opt['mail_body']);
		$mail_body=str_replace("#sip_address#", $sip_address, $mail_body);

		if (!send_mail($_POST['email'], $this->opt['mail_subject'], $mail_body)){
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
		parent::init();

		$this->reg = new Creg;				// create regular expressions class
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
		                             "value"=>$this->opt['terms'],
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

<?php
/**
 *	Application unit credentials
 *	
 *	@author     Karel Kozlik
 *	@version    $Id: apu_credentials.php,v 1.4 2007/02/14 16:46:30 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_credentials
 */ 

/**
 *	Application unit credentials
 *
 *
 *	This application unit is used for editing credentials of users
 *	   
 *	<pre>
 *	Configuration:
 *	--------------
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
 *	</pre>
 *	@package    serweb
 *	@subpackage mod_credentials
 */

class apu_credentials extends apu_base_class{
	var $smarty_action='default';
	var $credentials;
	var $smarty_cred;
	var $edit_uname;
	var $edit_realm;
	var $edit_did;
	var $js_before = "";

	/** 
	 *	return required data layer methods - static class 
	 *
	 *	@return array	array of required data layer methods
	 */
	function get_required_data_layer_methods(){
		return array('get_credentials', 'add_credentials', 'del_credentials',
		             'update_credentials');
	}

	/**
	 *	return array of strings - required javascript files 
	 *
	 *	@return array	array of required javascript files
	 */
	function get_required_javascript(){
		return array();
	}
	
	/**
	 *	constructor 
	 *	
	 *	initialize internal variables
	 */
	function apu_credentials(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['smarty_credentials'] =		'credentials';
		$this->opt['smarty_clear_text_pw'] = 	'clear_text_pw';

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
		
		
	}

	/**
	 *	this metod is called always at begining - initialize variables
	 */
	function init(){
		parent::init();
	}

	/**
	 *	Format credentials for smarty
	 *	and store them to $this->smarty_cred array
	 */
	function format_credentials(){
		global $sess, $config;
	
		$this->smarty_cred = array();
		foreach($this->credentials as $k => $v){
		
			/* skip the edited row */
			if ($v->get_uname() == $this->edit_uname and
			    $v->get_realm() == $this->edit_realm and
				(!$config->auth['use_did'] or
				 $v->get_did() == $this->edit_did))     continue;
		
			if (false === $this->smarty_cred[$k] = $v->to_smarty()) return false;
			
			$this->smarty_cred[$k]['url_edit'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
			                                                   "&e_un=".RawURLEncode($v->get_uname()).
															   "&e_re=".RawURLEncode($v->get_realm()).
															   "&e_did=".RawURLEncode($v->get_did()).
															   "&edit=1");
			$this->smarty_cred[$k]['url_dele'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
			                                                   "&e_un=".RawURLEncode($v->get_uname()).
															   "&e_re=".RawURLEncode($v->get_realm()).
															   "&e_did=".RawURLEncode($v->get_did()).
															   "&dele=1");
		}
		
		return true;
	}

	function get_realm($did){
		global $config;
		
		$opt=array("did"=>$_POST['cr_domain']);
		if (false === $realm = Attributes::get_attribute($config->attr_names['digest_realm'], $opt)) return false;

		return $realm;	
	}

	function action_add(&$errors){
		global $data, $config;

		$opt = array('for_ser'    => !empty($_POST['cr_for_ser']),
		             'for_serweb' => !empty($_POST['cr_for_serweb']));


		if (false === $realm = $this->get_realm($_POST['cr_domain'])) return false;

		if (false === $data->add_credentials($this->controler->user_id->get_uid(),
		                                     $_POST['cr_domain'],
		                                     $_POST['cr_uname'],
											 $realm,
											 $_POST['cr_passw'],
											 $opt)) return false;

		return true;
	}
	
	function action_delete(&$errors){
		global $data;

		if (false === $data->del_credentials($this->controler->user_id->get_uid(),
		                                     $this->edit_did,
		                                     $this->edit_uname,
											 $this->edit_realm,
											 null)) return false;

		return true;
	}
	
	/**
	 *	Method perform action update
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_update(&$errors){
		global $data, $config;

		$new_credential = null;

		foreach($this->credentials as $k => $v){
			if ($v->get_uname() == $this->edit_uname and
			    $v->get_realm() == $this->edit_realm and
				(!$config->auth['use_did'] or
				 $v->get_did() == $this->edit_did)){

				$new_credential = &$this->credentials[$k];
				break;
			}
		}

		if (is_null($new_credential)) {
			ErrorHandler::add_error("Can't find credential for change");
			return false;
		}

		if (false === $realm = $this->get_realm($_POST['cr_domain'])) return false;

		$new_credential->set_uname($_POST['cr_uname']);
		$new_credential->set_did($_POST['cr_domain']);
		$new_credential->set_realm($realm);
		$new_credential->set_password($_POST['cr_passw']);

		if (empty($_POST['cr_for_ser'])) $new_credential->reset_for_ser();
		else                             $new_credential->set_for_ser();

		if (empty($_POST['cr_for_serweb'])) $new_credential->reset_for_serweb();
		else                                $new_credential->set_for_serweb();

		$new_credential->recalc_ha1();

		if (false === $data->update_credentials($this->controler->user_id->get_uid(),
		                                        $this->edit_did,
		                                        $this->edit_uname,
		                                        $this->edit_realm,
		                                        $new_credential,
		                                        null)) return false;

		return true;
	}
	
	function action_edit(&$errors){
		if (false === $this->format_credentials()) return false;
		return true;
	}

	function action_default(&$errors){
		if (false === $this->format_credentials()) return false;
		return true;
	}

	/**
	 *	check _get and _post arrays and determine what we will do 
	 */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			if (!empty($_POST['cr_e_un'])){
				$this->edit_uname = $_POST['cr_e_un'];
				$this->edit_realm = $_POST['cr_e_re'];
				$this->edit_did   = $_POST['cr_e_did'];
				$this->action=array('action'=>"update",
				                    'validate_form'=>true,
									'reload'=>true);
			}
			else{
				$this->action=array('action'=>"add",
				                    'validate_form'=>true,
									'reload'=>true);
			}
		}
		elseif (!empty($_GET['edit']) and isset($_GET['e_un']) and isset($_GET['e_re'])){
			$this->edit_uname = $_GET['e_un'];
			$this->edit_realm = $_GET['e_re'];
			$this->edit_did   = $_GET['e_did'];
			$this->action=array('action'=>"edit",
			                    'validate_form'=>false,
								'reload'=>false);
		}
		elseif (!empty($_GET['dele']) and isset($_GET['e_un']) and isset($_GET['e_re'])){
			$this->edit_uname = $_GET['e_un'];
			$this->edit_realm = $_GET['e_re'];
			$this->edit_did   = $_GET['e_did'];
			$this->action=array('action'=>"delete",
			                    'validate_form'=>false,
								'reload'=>true);
		}
		else $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
	}
	
	/**
	 *	create html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return null			FALSE on failure
	 */
	function create_html_form(&$errors){
		parent::create_html_form($errors);
		global $data, $lang_str, $config;

		/* get list of credentials */
		if (false === $this->credentials = $data->get_credentials($this->controler->user_id->get_uid(), null)) return false;

		$did = $uname = $realm = $password = null;
		$for_ser = $for_serweb = true;
		if ($this->action['action'] == 'edit'){
			foreach($this->credentials as $k => $v){
				if ($v->get_uname() == $this->edit_uname and
				    $v->get_realm() == $this->edit_realm and
					(!$config->auth['use_did'] or
					 $v->get_did() == $this->edit_did)){
				    
				    $uname = $v->get_uname();
				    $realm = $v->get_realm();
				    $did   = $v->get_did();
				    $password = $v->get_password();
				    $for_ser = $v->is_for_ser();
				    $for_serweb = $v->is_for_serweb();
					break;
				}
			}
		}

		$domains = &Domains::singleton();
		if (false === $domain_names = $domains->get_id_name_pairs()) return false;

		$dom_options = array();
		foreach ($domain_names as $k => $v) 
			$dom_options[]=array("label"=>$v, "value"=>$k);
	

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"cr_uname",
									 "size"=>16,
									 "maxlength"=>64,
		                             "value"=>$uname,
		                             "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_username'],
		                             "valid_regex"=>$config->username_regex,
		                             "valid_e"=>$lang_str['fe_uname_not_follow_conventions']));

		$this->f->add_element(array("type"=>"select",
									 "name"=>"cr_domain",
									 "options"=>$dom_options,
									 "value"=>$did,
									 "size"=>1));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"cr_passw",
									 "size"=>16,
									 "maxlength"=>28,
		                             "value"=>$password));

		$this->f->add_element(array("type"=>"checkbox",
		                             "name"=>"cr_for_ser",
		                             "value"=>"1",
		                             "checked"=>$for_ser));

		$this->f->add_element(array("type"=>"checkbox",
		                             "name"=>"cr_for_serweb",
		                             "value"=>"1",
		                             "checked"=>$for_serweb));

		$this->f->add_element(array("type"=>"hidden",
		                             "name"=>"cr_e_un",
		                             "value"=>$this->edit_uname));

		$this->f->add_element(array("type"=>"hidden",
		                             "name"=>"cr_e_re",
		                             "value"=>$this->edit_realm));

		$this->f->add_element(array("type"=>"hidden",
		                             "name"=>"cr_e_did",
		                             "value"=>$this->edit_did));

		if (!$config->clear_text_pw){
			$this->js_before .= "
				if (f.cr_e_un.value != '' &&   //perform this check only when modifing credential, not when creating new one
				    f.cr_e_did.value != f.cr_domain[f.cr_domain.selectedIndex].value &&
				    f.cr_passw.value == ''){
					
					alert('".addslashes($lang_str['err_credential_changed_domain'])."');
					f.cr_passw.focus();
					return (false);
				}
			
			";
		}
	}

	/**
	 *	validate html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return bool			TRUE if given values of form are OK, FALSE otherwise
	 */
	function validate_form(&$errors){
		global $lang_str, $config;
		
		if (false === parent::validate_form($errors)) return false;

		if (!$config->clear_text_pw and 
		    !empty($_POST['cr_e_un']) and   //perform this check only when modifing credential, not when creating new one
		    $_POST['cr_e_did'] != $_POST['cr_domain'] and empty($_POST['cr_passw'])){
			$errors[] = $lang_str['err_credential_changed_domain'];
			return false;
		}

		return true;
	}
	
	
	/**
	 *	add messages to given array 
	 *
	 *	@param array $msgs	array of messages
	 */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_my_apu_updated']) and $_GET['m_my_apu_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}
	}

	/**
	 *	assign variables to smarty 
	 */
	function pass_values_to_html(){
		global $smarty, $config;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_credentials'], $this->smarty_cred);
		$smarty->assign($this->opt['smarty_clear_text_pw'], $config->clear_text_pw);
	}
	
	/**
	 *	return info need to assign html form to smarty 
	 */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => '',
					 'before'      => $this->js_before);
	}
}

?>

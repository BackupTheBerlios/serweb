<?
/*
 * $Id: apu_reg_confirmation.php,v 1.3 2006/04/04 10:33:24 kozlik Exp $
 */ 

/* Application unit reg_confirmation */

/*
   This application unit is used for confirmation of user registration
   
   Configuration:
   --------------
   
   'setup_jabber_account'		(bool) default: $config->setup_jabber_account
     If true, jabber account is setupped for users

	'create_numeric_alias'		(bool) default: $config->create_numeric_alias_to_new_users
	 If true, create numeric alias for new subscriber
	
	 
   'smarty_action'				name of smarty variable - see below
   'smarty_status'				name of smarty variable - see below
   
   Exported smarty variables:
   --------------------------
	 
   opt['smarty_action']			(action)
	  tells what should smarty display. Values:
   	  'default' - 
	  'successfull' - when user registration was confirmed

   opt['smarty_status']
	  tells aditional info about creating account. Value:
      'already_done'  -  if account was already created
	  'jabber_failed' -  if serweb account was created but jabber account not
	  'ok'
   
*/

class apu_reg_confirmation extends apu_base_class{
	var $smarty_action='default';
	var $nr;		//confirmation number
	var $wrong_nr = false;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_attr_by_val', 'enable_user');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_reg_confirmation(){
		global $lang_str, $config;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['setup_jabber_account'] =		$config->setup_jabber_account;

		/* alias generation */
		$this->opt['create_numeric_alias'] =		$config->create_numeric_alias_to_new_users;
		
		/*** names of variables assigned to smarty ***/
		/* smarty action */
		$this->opt['smarty_action'] =		'action';

		$this->opt['smarty_status']	=		'status';
	}

	/* this metod is called always at begining */
	function init(){
		parent::init();
	}

	function action_confirm_reg(&$errors){
		global $data, $config, $lang_str;

		if (isset($_GET['pr'])){
			$proxy['proxy'] = base64_decode($_GET['pr']);
			
			if ($proxy['proxy']){
				if (false === $data->set_home_proxy($proxy['proxy'])) return false;
			} 
		}
		
		if (isModuleLoaded('xxl') and !$proxy['proxy']){
			$errors[] = $lang_str['err_reg_conf_not_exists_conf_num'];
			return false;
		}

		$an = &$config->attr_names;

		/* get uid */
		$o = array('name' =>  $an['confirmation'],
		           'value' => $this->nr);
		if (false === $attrs = $data->get_attr_by_val("user", $o)) return false;

		if (empty($attrs[0]['id'])) {
			$this->wrong_nr = true;
			ErrorHandler::add_error($lang_str['err_reg_conf_not_exists_conf_num']);
			return false;
		}

		$uid = $attrs[0]['id'];

		if (false === $data->transaction_start()) return false;


		$o = array("uid" => $uid,
		           "disable" => false);
		if (false === $data->enable_user($o)) {
			$data->transaction_rollback();
			return false;
		}

		$user_attrs = &User_Attrs::singleton($uid);
		if (false === $user_attrs->unset_attribute($an['confirmation'])) {
			$data->transaction_rollback();
			return false;
		}
		if (false === $user_attrs->unset_attribute($an['pending_ts'])) {
			$data->transaction_rollback();
			return false;
		}
			
		if (false === $data->transaction_commit()) return false;
	
		if ($this->opt['setup_jabber_account']) {
			ErrorHandler::add_error("Registration in jabber not maintained, please set \$config->setup_jabber_account=false in config file.");
			
			# Jabber Gateway registration
			$res = reg_jab($user_id->uname);
			if($res!=0) {
				$res=$res+1; 
				log_errors(PEAR::raise_error("jabber registration failed: <".$user_id->uname."> [".$res."]"), $errors);
				return array("confirmation_ok=1", "conf_jabber_failed=1");
			}
		}

		return array("confirmation_ok=1");
	}
	
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if (isset($_GET['nr'])){
			$this->nr = $_GET['nr'];
			$this->action=array('action'=>"confirm_reg",
			                    'validate_form'=>false,
								'reload'=>true);
		}
		else $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		if (isset($_GET['confirmation_ok']) and $_GET['confirmation_ok']) $this->smarty_action = "successfull";

	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		
		if ($this->wrong_nr) 
			$smarty->assign($this->opt['smarty_status'], "nr_not_exists");
		elseif (isset($_GET['conf_jabber_failed']) and $_GET['conf_jabber_failed']) 
			$smarty->assign($this->opt['smarty_status'], "jabber_failed");
		else
			$smarty->assign($this->opt['smarty_status'], "ok");
	}
	
}


?>

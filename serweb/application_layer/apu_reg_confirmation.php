<?
/*
 * $Id: apu_reg_confirmation.php,v 1.2 2005/05/02 15:03:45 kozlik Exp $
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
	
	'add_to_aliases_table_too'	(bool) default: $config->copy_new_subscribers_to_aliases_table
	 If true, serweb will add new subscriber also into aliases table instead of into subscriber table only
	 
	 
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

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('move_user_from_pending_to_subscriber', 'get_new_alias_number', 'add_new_alias',
									'del_user_from_pending', 'del_user_from_subscriber', 'delete_alias');
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
		$this->opt['add_to_aliases_table_too'] =	$config->copy_new_subscribers_to_aliases_table;
		
		/*** names of variables assigned to smarty ***/
		/* smarty action */
		$this->opt['smarty_action'] =		'action';

		$this->opt['smarty_status']	=		'status';
	}

	function action_confirm_reg(&$errors){
		global $data, $config;

		if (isset($_GET['pr'])){
			$proxy['proxy'] = base64_decode($_GET['pr']);
			
			if ($proxy['proxy']){
				if (false === $data->set_home_proxy($proxy['proxy'])) return false;
			} 
		}
		
		if ($config->enable_XXL and !$proxy['proxy']){
			$errors[] = $lang_str['err_reg_conf_not_exists_conf_num'];
			return false;
		}

		// move user from table pending to table subscriber
		if (false === $user_id=$data->move_user_from_pending_to_subscriber($this->nr, $errors)) return false;

		// if user was already moved
		if (true === $user_id) {
			return array("confirmation_ok=1", "conf_already_done=1");
		} 
		
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
			if ($remove_from_subscriber) $data->del_user_from_subscriber($this->nr, $errors);
			if ($remove_alias) $data->delete_alias($user_id, $user_id->uname, $user_id->domain, $errors);
			return false;
		}
			
		// delete user from table pending			
		$data->del_user_from_pending($this->nr, $errors);
	
		if ($this->opt['setup_jabber_account']) {
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
	
	/* this metod is called always at begining */
	function init(){
		parent::init();
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
	
	/* create html form */
	function create_html_form(&$errors){
		parent::create_html_form($errors);
	}

	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;
		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		if (isset($_GET['confirmation_ok']) and $_GET['confirmation_ok']) $this->smarty_action = "successfull";

	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		
		if (isset($_GET['conf_already_done']) and $_GET['conf_already_done']) 
			$smarty->assign($this->opt['smarty_status'], "already_done");
		elseif (isset($_GET['conf_jabber_failed']) and $_GET['conf_jabber_failed']) 
			$smarty->assign($this->opt['smarty_status'], "jabber_failed");
		else
			$smarty->assign($this->opt['smarty_status'], "ok");
	}
	
}


?>

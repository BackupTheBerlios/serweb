<?php
/**
 * Application unit domain 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_domain.php,v 1.17 2006/05/03 13:41:07 kozlik Exp $
 * @package   serweb
 */ 

/**
 *	Application unit domain 
 *
 *
 *	This application unit is used for change domain names and owner
 *	   
 *	Configuration:
 *	--------------
 *	
 *	'redirect_on_update'		(string) default: ''
 *	 name of script to which is browser redirected after succesfull update
 *	 if empty, browser isn't redirected
 *	
 *	'redirect_on_disable'		(string) default: ''
 *	 name of script to which is browser redirected after disable or enable domain 
 *	 if empty, browser isn't redirected
 *	
 *	'redirect_on_delete'		(string) default: ''
 *	 name of script to which is browser redirected after succesfull delete of domain
 *	 if empty, browser isn't redirected
 *	
 *	'no_domain_name_e'			(string) default: $lang_str['no_domain_name_is_set']
 *	 error message displayed when any domain names is not set
 *	
 *	'preselected_customer'		(string) default: null
 *	 ID of preselected customer. If is set, customer with given ID is preselected
 *	 in select box.
 *	
 *	'msg_delete'				default: $lang_str['msg_domain_deleted_s'] and $lang_str['msg_domain_deleted_l']
 *	 message which should be showed on domain delete - assoc array with keys 'short' and 'long'
 *								
 *	'form_name'					(string) default: ''
 *	 name of html form
 *	
 *	'form_submit'				(assoc)
 *	 assotiative array describe submit element of form. For details see description 
 *	 of method add_submit in class form_ext
 *	 
 *	'form_add_submit'			(assoc)
 *	 assotiative array describe submit element for creating new alias of domain.
 *   For details see description of method add_submit in class form_ext
 *	
 *	'smarty_form'				name of smarty variable - see below
 *	'smarty_action'				name of smarty variable - see below
 *	'smarty_id'					name of smarty variable - see below
 *	'smarty_dom_names'			name of smarty variable - see below
 *	'smarty_customers'			name of smarty variable - see below
 *	'smarty_admins'				name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_action']		(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'was_deleted' - when domain was deleted
 *	 
 *	opt['smarty_id'] 			(dom_id)
 *	 contain id of currently edited domain
 *	 
 *	opt['smarty_dom_names'] 	(dom_names)
 *	 array containing domain aliases
 *	 
 *	opt['smarty_customers'] 	(customers)
 *	 array containing customers
 *	 
 *	opt['smarty_admins'] 		(admins)
 *	 array containing admins of domain
 *	 
 */


class apu_domain extends apu_base_class{
	var $smarty_action='default';
	/** id of edited domain */
	var $id = null;
	/** array of customers */
	var $customers = array();
	/** array of names of domain with $this->id */
	var $dom_names = array();
	/** owner of this domain */
	var $owner = null;
	/** domain attributes */
	var $domain_attrs = array();
	/** admins of domain */
	var $admins;
	/** used by function generate_domain_id and revert_domain_id */
	var $revert_domain_id = false;
	
	/** 
	 *	return required data layer methods - static class 
	 *
	 *	@return array	array of required data layer methods
	 */
	function get_required_data_layer_methods(){
		return array('get_domain', 'get_customers', 
			'get_new_domain_id', 'enable_domain', 'del_domain_alias', 
			'add_domain_alias', 'reload_domains', 'mark_domain_deleted',
			'get_users', 'set_domain_canon');
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
	function apu_domain(){
		global $lang_str, $sess_lang, $config;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['redirect_on_disable'] = "";
		$this->opt['redirect_on_update']  = "";
		$this->opt['redirect_on_delete']  = "";

		$this->opt['preselected_customer']  = null;

		$this->opt['no_domain_name_e'] = $lang_str['no_domain_name_is_set'];
		
		$this->opt['form_add_submit']=array('type' => 'image',
										'text' => $lang_str['b_add'],
										'src'  => get_path_to_buttons("btn_add.gif", $sess_lang));

		/* messages */
		$this->opt['msg_delete']['short'] =	&$lang_str['msg_domain_deleted_s'];
		$this->opt['msg_delete']['long']  =	&$lang_str['msg_domain_deleted_l'];

		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';

		$this->opt['smarty_id'] =			'dom_id';
		
		$this->opt['smarty_dom_names'] =	'dom_names';
		
		$this->opt['smarty_customers'] =	'customers';
				
		$this->opt['smarty_admins'] =		'admins';
	}

	/**
	 *	this metod is called always at begining - initialize variables
	 */
	function init(){
		parent::init();
	}


	/**
	 *	Method create or remove symlinks for all aliases of the domain
	 *
	 *	Method create or remove symlinks in directory with domain specific config and 
	 *	in directory with virtual hosts (for purpose of apache)
	 *
	 *	@param bool  $create	if true, function create symlinks, otherwise remove them
	 *	@param array $errors	array with error messages
	 *	@return bool			return TRUE on success, FALSE on failure
	 */

	function create_or_remove_all_symlinks($create, &$errors){
		global $data;
		
		if (false === $this->get_domain_names($errors)) return false;
		
		foreach($this->dom_names as $v){
			if ($create){
				if (false === domain_create_symlinks($this->id, $v['name'], $errors)) return false;
			}
			else{
				if (false === domain_remove_symlinks($v['name'], $errors)) return false;
			}
		}
		return true;
	}

	/**
	 *	Generete new domain id if is not set
	 *
	 *	@param array $errors	array with error messages
	 *	@return bool			return TRUE on success, FALSE on failure
	 */
	 
	function generate_domain_id($domainname, &$errors){
		global $data, $config;
		
		/* if domain id is already set, return */
		if (!is_null($this->id)) return true;

		$an = &$config->attr_names;

		/* get format of did to generate */
		$ga = &Global_attrs::singleton();
		if (false === $format = $ga->get_attribute($an['did_format'])) return false;


		switch ($format){
		/* numeric DID */
		case 1:
			if (false === $did = $data->get_new_domain_id(null, $errors)) return false;
			break;
			
		/* UUID by rfc4122 */
		case 2:
			$did = rfc4122_uuid();

			/* check if did doesn't exists */
			$dh = &Domains::singleton();
			if (false === $dids = $dh->get_all_dids()) return false; 
			
			while (in_array($did, $dids, true)){
				$did = rfc4122_uuid();
			}
			break;

		/* DID as 'domainname' */
		case 0:
		default:  /* if format of UIDs is not set, assume the first choice */

			if (!$domainname) $domainname = "default";	// if domain name is not provided
			$did = $domainname;
			
			/* check if did doesn't exists */
			$dh = &Domains::singleton();
			if (false === $dids = $dh->get_all_dids()) return false; 

			$i = 0;
			while (in_array($did, $dids, true)){
 				$did = $domainname."_".$i++;
			}
			break;
		}

		$this->id = $did;
		$this->revert_domain_id = true;
		$this->controler->set_domain_id($this->id);

		return true;

	}

	/**
	 *	Revert domain id back to null value in the case creation of domain was not successfull
	 *
	 */

	function revert_domain_id(){
		if ($this->revert_domain_id){
			$this->id = NULL;
			$this->controler->set_domain_id($this->id);
		}

		return;
	}

	/**
	 *	Method obtain a list of all domain names (aliases) and store it into 
	 *	variable $this->dom_names
	 *
	 *	@param array $errors	array with error messages
	 *	@return bool			return TRUE on success, FALSE on failure
	 */

	function get_domain_names(&$errors){
		global $data, $sess;

		/* if domain id is still not set, do nothing */
		if (is_null($this->id)){
			$this->dom_names = array();
			return true;
		}

		$opt = array();
		$opt['filter']['did'] = $this->id;
	
		if (false === $this->dom_names = $data->get_domain($opt)) return false;

		foreach($this->dom_names as $key=>$val){
			$this->dom_names[$key]['url_dele'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&dele_name=".RawURLEncode($val['name']));
			$this->dom_names[$key]['url_set_canon'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&set_canon=1&dom_name=".RawURLEncode($val['name']));
			$this->dom_names[$key]['allow_dele'] = (!$val['canon'] and (count($this->dom_names) > 1) ? true : false);
		}

		return true;		
	}

	/**
	 *	Method obtain a list of admins of domain and store it into 
	 *	variable $this->admins
	 *
	 *	@param array $errors	array with error messages
	 *	@return bool			return TRUE on success, FALSE on failure
	 */

	function get_admins(&$errors){
		global $data, $sess, $config;
		
		$an = &$config->attr_names;
		
		if (!isset($this->domain_attrs[$an['admin']])){
			$admins = array();
		} else{
			$admins = $this->domain_attrs[$an['admin']];
		}

		if (!count($admins)) { $this->admins = array(); return true; }


		$o = array("only_users" => $admins,
		           "get_user_aliases" => false,
				   "return_all" => true);

		if (false === $this->admins = $data->get_users(array(), $o)) return false;


		foreach ($this->admins as $k => $v){
			$this->admins[$k]['url_unset_admin'] = 
				$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
						"&do_unset_admin=".RawURLEncode($this->opt['instance_id']).
						"&".$v['get_param']);
		}

		return true;
	}
	
	/**
	 *	Method enable or disable (depending on $_GET params) domain
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_enable_domain(&$errors){
		global $data;

		if (is_null($this->id)) {
			log_errors(PEAR::raiseError('domain ID is not specified'), $errors); 
			return false;
		}

		FileJournal::clear();

		if (isset($_GET['enable'])){
			$opt['did'] = $this->id;
			$opt['disable'] = false;

			if (false === $this->create_or_remove_all_symlinks(true, $errors)) {
				FileJournal::rollback();
				return false;
			}
		}
		else {
			$opt['did'] = $this->id;
			$opt['disable'] = true;

			if (false === $this->create_or_remove_all_symlinks(false, $errors)) {
				FileJournal::rollback();
				return false;
			}
		}

		if (false === $data->enable_domain($opt)) {
			FileJournal::rollback();
			return false;
		}

		/* notify SER to reload domains */
		if (false === $data->reload_domains(null, $errors)) return false;

		if ($this->opt['redirect_on_disable']){
			$this->controler->change_url_for_reload($this->opt['redirect_on_disable']);
		}

		if (isset($_GET['enable']))
			return array("m_do_enabled=".RawURLEncode($this->opt['instance_id']));
		else
			return array("m_do_disabled=".RawURLEncode($this->opt['instance_id']));
	}

	/**
	 *	Method delete domain
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_delete_domain(&$errors){
		global $data;

		if (is_null($this->id)) {
			log_errors(PEAR::raiseError('domain ID is not specified'), $errors); 
			return false;
		}

		$opt['did'] = $this->id;
		if (false === $this->create_or_remove_all_symlinks(false, $errors)) return false;

		if (false === $data->mark_domain_deleted($opt)) return false;

		/* notify SER to reload domains */
		if (false === $data->reload_domains(null, $errors)) return false;

		if ($this->opt['redirect_on_delete']){
			$this->controler->change_url_for_reload($this->opt['redirect_on_delete']);
		}

		return array("m_do_deleted=".RawURLEncode($this->opt['instance_id']));
	}

	/**
	 *	Method delete alias of the domain. Alias name depend on $_GET param
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_delete_domain_alias(&$errors){
		global $data, $lang_str;

		if (is_null($this->id)) {
			log_errors(PEAR::raiseError('domain ID is not specified'), $errors); 
			return false;
		}

		if (count($this->dom_names) <= 1){
			$errors[] = $lang_str['can_not_del_last_dom_name'];
			return false;			
		}

		$opt['id'] = $this->id;
		$opt['name'] = $_GET['dele_name'];

		if (false === domain_remove_symlinks($opt['name'], $errors)) return false;

		if (false === $data->del_domain_alias($opt, $errors)) return false;

		/* notify SER to reload domains */
		if (false === $data->reload_domains(null, $errors)) return false;

		return array("m_do_alias_deleted=".RawURLEncode($this->opt['instance_id']));
	}


	/**
	 *	Method set domain name to be canonical. Domain name depend on $_GET param
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_set_canonical(&$errors){
		global $data, $lang_str;

		if (is_null($this->id)) {
			log_errors(PEAR::raiseError('domain ID is not specified'), $errors); 
			return false;
		}

		if (empty($_GET['dom_name'])) {
			log_errors(PEAR::raiseError('domain name is not specified'), $errors); 
			return false;
		}

		if (false === $data->set_domain_canon($this->id, $_GET['dom_name'], null)) return false;

		return array("m_do_set_canon=".RawURLEncode($this->opt['instance_id']));
	}

	/**
	 *	Method create new alias of the domain. 
	 *
	 *	@param string $alias	name of new alias
	 *	@param array $errors	array with error messages
	 *	@return bool			TRUE on success, FALSE on error
	 */
	function add_alias($alias, &$errors){
		global $data;

		$values['id'] = $this->id;
		$values['name'] = $alias;
		
		if (count($this->dom_names)){
			$disabled = true;
			// domain is disabled if all domain names are disabled
			foreach($this->dom_names as $v){
				$disabled = ($disabled and ((bool)$v['disabled']));
			}
		}
		else{
			// POZOR! tady muze obcas nastavat problem pokud neexistujou zaznamy v DB
			$disabled = false;
		}

		$o = array();
		$o['disabled'] = $disabled;
		$o['set_canon'] = !(bool)count($this->dom_names);

		if (false === $data->add_domain_alias($values, $o, $errors)) {
			return false;
		}
				
		/* create symlinks and notify ser only if domain isn't disabled */
		if (!$disabled){
			if (false === domain_create_symlinks($this->id, $values['name'], $errors)) return false;
	
			/* notify SER to reload domains */
			if (false === $data->reload_domains(null, $errors)) return false;
		}
	
		return true;
	}

	/**
	 *	Method update the owner of domain and diges realm. 
	 *
	 *	@param string $id		ID of owner (customer)
	 *	@param array $errors	array with error messages
	 *	@return bool			TRUE on success, FALSE on error
	 */
	function update_domain_attrs($id, $alias, &$errors){
		global $config;
		
		$an = &$config->attr_names;

		$domain_attrs = &Domain_Attrs::singleton($this->id);

		if (!is_null($id) and $id != $this->owner['id']){
			if ($id == -1){
				if (false === $domain_attrs->unset_attribute($an['dom_owner'])){
					return false;
				}
			}
			else{
				if (false === $domain_attrs->set_attribute($an['dom_owner'], $id)){
					return false;
				}
			}
		}

		/*
		 *	If digest realm is not set, set it by the canonical domain name
		 */
		if (!isset($this->domain_attrs[$an['digest_realm']])){
			$digest_realm = "";

			foreach($this->dom_names as $v){
				if ($v['canon']) {
					$digest_realm = $v['name'];
					break;
				}
			}

			if (!$digest_realm and !empty($alias)) $digest_realm = $alias;

			if ($digest_realm and 
				false === $domain_attrs->set_attribute($an['digest_realm'], $digest_realm)){
				return false;
			}
		}


		return true;	
	}

	/**
	 *	Method create new alias of the domain and stay on the same page
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_add_alias(&$errors){
		global $data;

		$sem = new Shm_Semaphore(__FILE__, "s", 1, 0600);

		if (false === $data->transaction_start()) return false;
		FileJournal::clear();

		/* set semaphore to be sure there will not be generated same domain id for two domains */
		if (!$sem->acquire()){
			$data->transaction_rollback();
			return false;
		}

		if (false === $this->generate_domain_id($_POST['do_new_name'], $errors)) return false;

		if (!empty($_POST['do_new_name'])){
			if (false === $this->add_alias($_POST['do_new_name'], $errors)) {
				FileJournal::rollback();
				$data->transaction_rollback();
				$this->revert_domain_id();
				$sem->release();
				return false;
			}
		}

		$owner_id = $alias = null;
		if (isset($_POST['do_customer'])) $owner_id = $_POST['do_customer'];
		if (isset($_POST['do_new_name'])) $alias = $_POST['do_new_name'];
			
		if (false === $this->update_domain_attrs($owner_id, $alias, $errors)) {
			FileJournal::rollback();
			$data->transaction_rollback();
			$this->revert_domain_id();
			$sem->release();
			return false;
		}

		if (false === $data->transaction_commit()) return false;

		$sem->release();
		
		return array("m_do_alias_created=".RawURLEncode($this->opt['instance_id']));
	}

	/**
	 *	Method update the domain. 
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_update(&$errors){
		global $data, $config;

		$sem = new Shm_Semaphore(__FILE__, "s", 1, 0600);

		if (false === $data->transaction_start()) return false;
		FileJournal::clear();

		/* set semaphore to be sure there will not be generated same domain id for two domains */
		if (!$sem->acquire()){
			$data->transaction_rollback();
			return false;
		}

		if (false === $this->generate_domain_id($_POST['do_new_name'], $errors)) return false;

		if (!empty($_POST['do_new_name'])){
			if (false === $this->add_alias($_POST['do_new_name'], $errors)) {
				FileJournal::rollback();
				$data->transaction_rollback();
				$this->revert_domain_id();
				$sem->release();
				return false;
			}
		}

		$owner_id = $alias = null;
		if (isset($_POST['do_customer'])) $owner_id = $_POST['do_customer'];
		if (isset($_POST['do_new_name'])) $alias = $_POST['do_new_name'];
			
		if (false === $this->update_domain_attrs($owner_id, $alias, $errors)) {
			FileJournal::rollback();
			$data->transaction_rollback();
			$this->revert_domain_id();
			$sem->release();
			return false;
		}


		if (false === $data->transaction_commit()) return false;

		$sem->release();

		if ($this->opt['redirect_on_update']){
			$this->controler->change_url_for_reload($this->opt['redirect_on_update']);
		}

		return array("m_do_updated=".RawURLEncode($this->opt['instance_id']));
	}

	/**
	 *	Unset admin of domain
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */
	function action_unset_admin(&$errors){
		global $config;
		
		$an = &$config->attr_names;

		$domain_attrs = &Domain_Attrs::singleton($this->id);
		$admins = $domain_attrs->get_attribute($an['admin']);
		if (is_null($admins)) $admins = array();

		foreach($admins as $k => $v){
			if ($v == $this->controler->user_id->get_uid()){
				unset($admins[$k]);
				break;
			}
		}
		
		if (false === $domain_attrs->set_attribute($an['admin'], $admins)) return false;

		return true;
	}

	/**
	 *	default action
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_default(&$errors){
		if (false === $this->get_admins($errors)) return false;
		return true;
	}
		
	/**
	 *	check _get and _post arrays and determine what we will do 
	 */
	function determine_action(){
		$this->id = $this->controler->domain_id;

		if ($this->was_form_submited()){	// Is there data to process?
			if (isset($_POST['do_okey_add_x'])){
				$this->action=array('action'=>"add_alias",
				                    'validate_form'=>true,
									'reload'=>true);
			}
			else{
				$this->action=array('action'=>"update",
				                    'validate_form'=>true,
									'reload'=>true);
			}

			return;
		}

		if (isset($_GET['new'])){
			$this->id = null;
			$this->controler->set_domain_id($this->id);
		    $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
			return;
		}

		if (isset($_GET['enable']) or isset($_GET['disable'])){
			$this->action=array('action'=>"enable_domain",
			                    'validate_form'=>false,
								'reload'=>true);
			return;
		}

		if (isset($_GET['delete'])){
			$this->action=array('action'=>"delete_domain",
			                    'validate_form'=>false,
								'reload'=>true);
			return;
		}

		if (isset($_GET['dele_name'])){
			$this->action=array('action'=>"delete_domain_alias",
			                    'validate_form'=>false,
								'reload'=>true);
			return;
		}

		if (isset($_GET['set_canon'])){
			$this->action=array('action'=>"set_canonical",
			                    'validate_form'=>false,
								'reload'=>true);
			return;
		}

		if (isset($_GET['do_unset_admin'])){
			$this->action=array('action'=>"unset_admin",
			                    'validate_form'=>false,
								'reload'=>true);
			return;
		}

/*		if (isset($_GET['edit']))*/
		$this->action=array('action'=>"default",
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
		global $data, $lang_str, $config;
		parent::create_html_form($errors);

		$an = &$config->attr_names;

		/* get list of customers */
		if (false === $this->customers = $data->get_customers(array(), $errors)) return false;
		
		/* if domain id is set */
		if (!is_null($this->id)){
			$domain_attrs = &Domain_Attrs::singleton($this->id);
			/* get domain attributes */
			if (false === $this->domain_attrs = $domain_attrs->get_attributes()) return false;
		}


		$options = array();
		$options[] = array('label' => "--- ".$lang_str['none']." ---", 'value' => -1);
		foreach ($this->customers as $v){
			$options[] = array('label' => $v['name'], 'value' => $v['cid']);
		}
		$selected_owner = null;

		/* set preselected customer */
		if (!is_null($this->opt['preselected_customer'])){
			$selected_owner   = $this->opt['preselected_customer'];
		}

		/* if domain id is set */
		if (!is_null($this->id)){
			/* get owner of the domain */
			if (isset($this->domain_attrs[$an['dom_owner']])){
				$this->owner['id']   = $this->domain_attrs[$an['dom_owner']];
				$this->owner['name'] = $this->customers[$this->owner['id']]['name'];
				$selected_owner      = $this->owner['id'];
			}
			
			/* get list of the domain names */
			if (false === $this->get_domain_names($errors)) return false;
		}

		$reg = &CReg::singleton();
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"do_new_name",
									 "size"=>16,
									 "maxlength"=>128,
		                             "value"=>"",
									 "valid_regex"=>"^(".$reg->host.")?$",
									 "valid_e"=>$lang_str['fe_not_valid_domainname']));
		
		$this->f->add_extra_submit("do_okey_add", $this->opt['form_add_submit']);

		$this->f->add_element(array("type"=>"select",
		                             "name"=>"do_customer",
									 "size"=>1,
									 "options"=>$options,
		                             "value"=>$selected_owner));

	}

	/**
	 *	validate html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return bool			TRUE if given values of form are OK, FALSE otherwise
	 */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;

		if ($this->action['action'] == "update" and 
		    !count($this->dom_names) and empty($_POST['do_new_name'])){
				$errors[] = $this->opt['no_domain_name_e'];
				return false;
		}

		if ($this->action['action'] == "add_alias" and 
		    empty($_POST['do_new_name'])){
				$errors[] = $this->opt['no_domain_name_e'];
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
		
		if (isset($_GET['m_do_deleted']) and $_GET['m_do_deleted'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_delete'];
			$this->smarty_action="was_deleted";
		}
	}

	/**
	 *	assign variables to smarty 
	 */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_id'], $this->id);
		$smarty->assign_by_ref($this->opt['smarty_dom_names'], $this->dom_names);
		$smarty->assign_by_ref($this->opt['smarty_customers'], $this->customers);
		$smarty->assign_by_ref($this->opt['smarty_admins'], $this->admins);
	}
	
	/**
	 *	return info need to assign html form to smarty 
	 */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => '',
					 'before'      => '');
	}
}

?>

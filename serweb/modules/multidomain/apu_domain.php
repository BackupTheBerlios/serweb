<?php
/**
 * Application unit domain 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_domain.php,v 1.2 2005/09/26 10:56:54 kozlik Exp $
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
 *	 name of script to which is browser redirected after disable or enable some 
 *	 alias of domain
 *	 if empty, browser isn't redirected
 *	
 *	'redirect_on_disable_all'	(string) default: ''
 *	 name of script to which is browser redirected after disable or enable whole 
 *	 domain
 *	 if empty, browser isn't redirected
 *	
 *	'no_domain_name_e'			(string) default: $lang_str['no_domain_name_is_set']
 *	 error message displayed when any domain names is not set
 *	
 *	'apache_vhosts_dir'			(string) default: $config->apache_vhosts_dir
 *	 Directory containing virtual hosts. Directory to which 
 *	 directive VirtualDocumentRoot from apache config pointing
 *	 For more info see http://httpd.apache.org/docs/2.1/vhosts/mass.html
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
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_action']		(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'was_updated' - when user submited form and data was succefully stored
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
	
	/** 
	 *	return required data layer methods - static class 
	 *
	 *	@return array	array of required data layer methods
	 */
	function get_required_data_layer_methods(){
		return array('get_owner_of_domain', 'get_domain', 'get_customers', 
			'get_new_domain_id', 'enable_domain', 'delete_domain', 
			'add_domain_alias', 'update_owner_of_domain', 'reload_domains');
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
		$this->opt['redirect_on_disable_all'] = "";
		$this->opt['redirect_on_update']  = "";

		$this->opt['no_domain_name_e'] = $lang_str['no_domain_name_is_set'];
		
		$this->opt['form_add_submit']=array('type' => 'image',
										'text' => $lang_str['b_add'],
										'src'  => get_path_to_buttons("btn_add.gif", $sess_lang));

		$this->opt['apache_vhosts_dir'] =	$config->apache_vhosts_dir;
		
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
				
	}

	/**
	 *	this metod is called always at begining - initialize variables
	 */
	function init(){
		parent::init();
	}

	/**
	 *  Method create directory with domain specific config 
	 *	Function create the directory by copy the html/domains/_default
	 *
	 *	@param array  $errors		array with error messages
	 *	@return bool				return TRUE on success, FALSE on failure
	 */
	 
	function create_domain_config_dir(&$errors){
		$serweb_root = dirname(dirname(dirname(__FILE__)))."/";

		$target = $serweb_root."html/domains/".$this->id;

		if (file_exists($target)) return true;

		if (false === copyr ($serweb_root."html/domains/_default", $target)){
			log_errors(PEAR::raiseError("Can't create domain specific config", NULL, NULL, 
			           NULL, "Can't create dicetory with domain config. Directory:".$target), $errors);
			return false;
		}
		
		return true;
	}

	/**
	 *	Method create symlinks for new domain name (alias)
	 *
	 *	Method create symlinks into directory with domain specific config and 
	 *	into directory with virtual hosts (for purpose of apache)
	 *
	 *	@param string $domain_name	name of domain (alias)
	 *	@param array  $errors		array with error messages
	 *	@return bool				return TRUE on success, FALSE on failure
	 */

	function create_symlinks($domain_name, &$errors){
		$serweb_root = dirname(dirname(dirname(__FILE__)))."/";

		$target = $serweb_root."html/domains/".$this->id;
		$file = $serweb_root."html/domains/".$domain_name;

		if (false === $this->create_domain_config_dir($errors)) return false;

		if (false === symlink($target, $file)){
			log_errors(PEAR::raiseError("Can't create domain specific config", NULL, NULL, 
			           NULL, "Can't create symlink. Link:".$file." target:".$target), $errors);
			return false;
		}
		
		$target = $serweb_root."html/";
		$file = $this->opt['apache_vhosts_dir'].$domain_name;

		if (false === symlink($target, $file)){
			log_errors(PEAR::raiseError("Can't create virtual server", NULL, NULL, 
			           NULL, "Can't create symlink. Link:".$file." target:".$target), $errors);
			return false;
		}
		return true;
	}

	/**
	 *	Method remove symlinks for domain name (alias)
	 *
	 *	Method remove symlinks from directory with domain specific config and 
	 *	from directory with virtual hosts (for purpose of apache)
	 *
	 *	@param string $domain_name	name of domain (alias)
	 *	@param array  $errors		array with error messages
	 *	@return bool				return TRUE on success, FALSE on failure
	 */

	function remove_symlinks($domain_name, &$errors){
		$serweb_root = dirname(dirname(dirname(__FILE__)))."/";

		$success = TRUE;
		$file = $serweb_root."html/domains/".$domain_name;
		if(file_exists($file)) $success = @unlink($file);
//		$success = @system('rm  "'.$file.'"');		// substitute of previous line for windows cygwim
		if (false === $success) {
			log_errors(PEAR::raiseError("Can't delete file", NULL, NULL, 
			           NULL, "Filename:".$file), $errors);
			return false;
		}
		
		$file = $this->opt['apache_vhosts_dir'].$domain_name;
		if(file_exists($file)) $success = @unlink($file);
//		$success = @system('rm  "'.$file.'"');		// substitute of previous line for windows cygwim
		if (false === $success) {
			log_errors(PEAR::raiseError("Can't delete file", NULL, NULL, 
			           NULL, "Filename:".$file), $errors);
			return false;
		}
		return true;
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
				if (false === $this->create_symlinks($v['name'], $errors)) return false;
			}
			else{
				if (false === $this->remove_symlinks($v['name'], $errors)) return false;
			}
		}
		return true;
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

		$opt = array();
		$opt['filter']['id'] = $this->id;
	
		if (false === $this->dom_names = $data->get_domain($opt, $errors)) return false;

		foreach($this->dom_names as $key=>$val){
			$this->dom_names[$key]['url_dele'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&dele_name=".RawURLEncode($val['name']));
			$this->dom_names[$key]['url_enable'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&enable_name=".RawURLEncode($val['name']));
			$this->dom_names[$key]['url_disable'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&disable_name=".RawURLEncode($val['name']));
		}

		return true;		
	}
	
	/**
	 *	Method enable or disable domain alias depending on $_GET params
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_enable_domain(&$errors){
		global $data;

		if (isset($_GET['enable_name'])){
			$opt['name'] = $_GET['enable_name'];
			$opt['disable'] = false;

			if (false === $this->create_symlinks($opt['name'], $errors)) return false;
		}
		else {
			$opt['name'] = $_GET['disable_name'];
			$opt['disable'] = true;
			
			if (false === $this->remove_symlinks($opt['name'], $errors)) return false;
		}

		if (false === $data->enable_domain($opt, $errors)) return false;

		/* notify SER to reload domains */
		if (false === $data->reload_domains(null, $errors)) return false;

		if ($this->opt['redirect_on_disable']){
			$this->controler->change_url_for_reload($this->opt['redirect_on_disable']);
		}

		return true;
	}

	/**
	 *	Method enable or disable (depending on $_GET params) all aliases of domain
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_enable_domain_all(&$errors){
		global $data;

		if (isset($_GET['enable_all'])){
			$opt['id'] = $this->id;
			$opt['disable'] = false;

			if (false === $this->create_or_remove_all_symlinks(true, $errors)) return false;
		}
		else {
			$opt['id'] = $this->id;
			$opt['disable'] = true;

			if (false === $this->create_or_remove_all_symlinks(false, $errors)) return false;
		}

		if (false === $data->enable_domain($opt, $errors)) return false;

		/* notify SER to reload domains */
		if (false === $data->reload_domains(null, $errors)) return false;

		if ($this->opt['redirect_on_disable_all']){
			$this->controler->change_url_for_reload($this->opt['redirect_on_disable_all']);
		}

		return true;
	}

	/**
	 *	Method delete alias of the domain. Alias name depend on $_GET param
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_delete_domain(&$errors){
		global $data;

		$opt['id'] = $this->id;
		$opt['name'] = $_GET['dele_name'];

		if (false === $this->remove_symlinks($opt['name'], $errors)) return false;

		if (false === $data->delete_domain($opt, $errors)) return false;

		/* notify SER to reload domains */
		if (false === $data->reload_domains(null, $errors)) return false;

		return true;
	}

	/**
	 *	Method create new alias of the domain. Alias name depend on $_POST param
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_add_alias(&$errors){
		global $data;

		$values['id'] = $this->id;
		$values['name'] = $_POST['do_new_name'];

		if (false === $this->create_symlinks($values['name'], $errors)) return false;
		
		if (false === $data->add_domain_alias($values, null, $errors)) return false;

		/* notify SER to reload domains */
		if (false === $data->reload_domains(null, $errors)) return false;
		
		return true;
	}

	/**
	 *	Method update the owner of domain. New owner depend on $_POST param
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_update(&$errors){
		global $data;

		if (!$this->owner or is_null($this->owner['id']))
			$opt['insert'] = true;
		else
			$opt['insert'] = false;

		if (false === $data->update_owner_of_domain($this->id, $_POST['do_customer'], $opt, $errors)) return false;


		if ($this->opt['redirect_on_update']){
			$this->controler->change_url_for_reload($this->opt['redirect_on_update']);
		}

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
		    $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
			return;
		}

		if (isset($_GET['enable_name']) or isset($_GET['disable_name'])){
			$this->action=array('action'=>"enable_domain",
			                    'validate_form'=>false,
								'reload'=>true);
			return;
		}

		if (isset($_GET['enable_all']) or isset($_GET['disable_all'])){
			$this->action=array('action'=>"enable_domain_all",
			                    'validate_form'=>false,
								'reload'=>true);
			return;
		}

		if (isset($_GET['dele_name'])){
			$this->action=array('action'=>"delete_domain",
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
		global $data, $lang_str;
		parent::create_html_form($errors);

		/* if domain id is not set, get new id */
		if (!$this->id) {
			if (false === $this->id = $data->get_new_domain_id(null, $errors)) return false;
			$this->controler->set_domain_id($this->id);
		}

		/* get list of customers */
		if (false === $this->customers = $data->get_customers(array(), $errors)) return false;

		$options = array();
		$options[] = array('label' => "--- ".$lang_str['none']." ---", 'value' => -1);
		foreach ($this->customers as $v){
			$options[] = array('label' => $v['name'], 'value' => $v['id']);
		}

		/* get owner of the domain */
		if (false === $this->owner = $data->get_owner_of_domain($this->id, null, $errors)) return false;

		/* get list of the domain names */
		if (false === $this->get_domain_names($errors)) return false;

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"do_new_name",
									 "size"=>16,
									 "maxlength"=>128,
		                             "value"=>""));
		
		$this->f->add_extra_submit("do_okey_add", $this->opt['form_add_submit']);

		$this->f->add_element(array("type"=>"select",
		                             "name"=>"do_customer",
									 "size"=>1,
									 "options"=>$options,
		                             "value"=>$this->owner['id']));

	}

	/**
	 *	validate html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return bool			TRUE if given values of form are OK, FALSE otherwise
	 */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;

		if ($this->action['action'] == "update" and !count($this->dom_names)){
			$errors[] = $this->opt['no_domain_name_e'];
			return false;
		}

		return true;
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
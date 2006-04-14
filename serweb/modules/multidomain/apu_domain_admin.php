<?php
/**
 * Application unit domain administrators 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_domain_admin.php,v 1.6 2006/04/14 19:15:35 kozlik Exp $
 * @package   serweb
 */ 

/**
 *	Application unit domain administrators
 *
 *
 *	This application unit is used for assign domains to administrators
 *	   
 *	Configuration:
 *	--------------
 *	
 *	'get_list_of_domains'		(bool) default: true
 *	 If is set to false list of domains in 'default' action is not returned.
 *	 This is only for better performance when the list does not needed.
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
 */

class apu_domain_admin extends apu_base_class{
	var $smarty_action='default';
	/** array of domains assigned to user */
	var $assigned_domains = array();
	/** array of rest domains (unassigned to user) */
	var $unassigned_domains = array();
	/** array of IDs of domains assigned to admin */
	var $assigned_ids = array();
	
	/** 
	 *	return required data layer methods - static class 
	 *
	 *	@return array	array of required data layer methods
	 */
	function get_required_data_layer_methods(){
		return array('get_domains', 'get_domains_of_admin');
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
	function apu_domain_admin(){
		global $lang_str, $sess_lang;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['get_list_of_domains'] =	true;

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
		
		$this->opt['smarty_assigned_domains'] =		'assigned_domains';
		$this->opt['smarty_unassigned_domains'] =	'unassigned_domains';
		
		$this->opt['form_submit']=array('type' => 'image',
										'text' => $lang_str['b_find'],
										'src'  => get_path_to_buttons("btn_find.gif", $sess_lang));
	}

	/**
	 *	this metod is called always at begining - initialize variables
	 */
	function init(){
		global $sess, $sess_apu_da;
		parent::init();

		/* registger session variable if still isn't registered */
		if (!$sess->is_registered('sess_apu_da')) $sess->register('sess_apu_da');
		
		/* set default value for session variable */
		if (!isset($sess_apu_da['filter'])){ 
			$sess_apu_da['filter']['id'] = '';
			$sess_apu_da['filter']['name'] = '';
			$sess_apu_da['filter']['customer'] = '';
		}

/*		if (!isset($sess_apu_da['act_row'])){ 
			$sess_apu_da['act_row'] = 0;
		}

		if (isset($_GET['act_row'])) 
			$sess_apu_dl['act_row'] = $_GET['act_row'];
*/
	}

	function get_domains(&$errors){
		global $data, $sess, $sess_apu_da;
		
		$opt = array();
		if (false === $this->assigned_ids = $data->get_domains_of_admin($this->controler->user_id->uuid, $opt)) return false;

		$opt = array("filter" => $sess_apu_da['filter'],
		             "return_all" => true,
		             "get_domain_names" => true);
		if (false === $domains = $data->get_domains($opt, $errors)) return false;

		$this->assigned_domains = array();
		$this->unassigned_domains = array();
		foreach ($domains as $k => $v){
			if ($v['id'] == '0') continue;	//skip default domain
			if (in_array($v['id'], $this->assigned_ids)) {
				$domains[$k]['url_unassign'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
						"&da_unassign=1&".$this->controler->domain_to_get_param($v['id']));
				$this->assigned_domains[] = &$domains[$k];
			}
			else {
				$domains[$k]['url_assign'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
						"&da_assign=1&".$this->controler->domain_to_get_param($v['id']));
				$this->unassigned_domains[] = &$domains[$k];
			}
		}
	
		return true;
	}
	
	/**
	 *	set to search filter by $_POST params
	 */
	function set_filter_by_posts(){
		global $sess_apu_da;

		/* show results from first row after form submit */
		//$sess_apu_da['act_row'] = 0;
		
		/* set search filter by values submited by form */
		$filter = &$sess_apu_da['filter'];
	
		if (isset($_POST['da_name'])) 		$filter['name']=$_POST['da_name'];
		if (isset($_POST['da_id']))   		$filter['id']=$_POST['da_id'];
		if (isset($_POST['da_customer']))	$filter['customer']=$_POST['da_customer'];

		$this->f->load_defaults();
	}

	function set_domain_admin($add){
		global $config;
		
		$an = &$config->attr_names;

		$da = &Domain_Attrs::Singleton($this->controler->domain_id);
		$admins = $da->get_attribute($an['admin']);
		if (is_null($admins)) $admins = array();

		if ($add){
			$admins[] = $this->controler->user_id->get_uid();
		}
		else {
			foreach($admins as $k => $v){
				if ($v == $this->controler->user_id->get_uid()){
					unset($admins[$k]);
					break;
				}
			}
		}

		
		if (false === $da->set_attribute($an['admin'], $admins)) return false;

		return true;
	}
	
	/**
	 *	Assign domain to admin
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */
	function action_assign_domain(&$errors){
		global $config;

		if (false === $this->set_domain_admin(true)) return false;
				
		return true;
	}
	
	/**
	 *	Unassign domain from admin
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */
	function action_unassign_domain(&$errors){
		global $config;
		
		if (false === $this->set_domain_admin(false)) return false;

		return true;
	}

	/**
	 *	Method perform default action
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_default(&$errors){

		if ($this->opt['get_list_of_domains']){
			if (!$this->get_domains($errors)) return false;
		}
	
		return true;
	}
	
	/**
	 *	check _get and _post arrays and determine what we will do 
	 */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			$this->action=array('action'=>"default",
			                    'validate_form'=>true,
								'reload'=>false);
			return;
		}

		if (isset($_GET['da_assign'])){
			$this->action=array('action'=>"assign_domain",
				                'validate_form'=>false,
								'reload'=>true);
			return;
		}

		if (isset($_GET['da_unassign'])){
			$this->action=array('action'=>"unassign_domain",
				                'validate_form'=>false,
								'reload'=>true);
			return;
		}

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
		global $sess_apu_da;
		parent::create_html_form($errors);

		$filter = &$sess_apu_da['filter'];

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"da_name",
									 "size"=>11,
									 "maxlength"=>128,
		                             "value"=>$filter['name']));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"da_id",
									 "size"=>11,
									 "maxlength"=>11,
		                             "value"=>$filter['id']));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"da_customer",
									 "size"=>11,
									 "maxlength"=>128,
		                             "value"=>$filter['customer']));
	}

	/**
	 *	validate html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return bool			TRUE if given values of form are OK, FALSE otherwise
	 */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;

		$this->set_filter_by_posts();
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
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_assigned_domains'], $this->assigned_domains);
		$smarty->assign_by_ref($this->opt['smarty_unassigned_domains'], $this->unassigned_domains);
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

<?php

/**
 * Application unit domain_list
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_domain_list.php,v 1.2 2005/09/26 10:56:54 kozlik Exp $
 * @package   serweb
 */ 

/**
 *	Application unit domain_list
 *
 *
 *	This application unit is used for display list of domains
 *	   
 *	Configuration:
 *	--------------
 *	
 *	'script_edit'				(string) default: 'domain_edit.php'
 *	 name of script which contain apu_domain
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
 *	'smarty_pager'				name of smarty variable - see below
 *	'smarty_domains'			name of smarty variable - see below
 *	'smarty_url_new_domain'		name of smarty variable - see below
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
 *	opt['smarty_pager']			(pager)
 *	 associative array containing size of result and which page is returned
 *	
 *	opt['smarty_domains'] 		(domains)	
 *	 associative array containing domains
 *	 The array have same keys as function get_domains (from data layer) returned. 
 *	
 *	opt['smarty_url_new_domain']	(url_new_domain)
 *	 contain url of link for create new domain
 */

class apu_domain_list extends apu_base_class{
	var $smarty_action='default';
	var $domains = array();

	/** 
	 *	return required data layer methods - static class 
	 *
	 *	@return array	array of required data layer methods
	 */
	function get_required_data_layer_methods(){
		return array('get_domains');
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
	function apu_domain_list(){
		global $lang_str, $sess_lang;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['script_edit'] =			'domain_edit.php';

		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';
		/* pager */
		$this->opt['smarty_pager'] =		'pager';

		$this->opt['smarty_domains'] = 	    'domains';

		$this->opt['smarty_url_new_domain']	= 'url_new_domain';
		
		$this->opt['form_submit']=array('type' => 'image',
										'text' => $lang_str['b_find'],
										'src'  => get_path_to_buttons("btn_find.gif", $sess_lang));
		
	}

	/**
	 *	this metod is called always at begining - initialize variables
	 */
	function init(){
		global $sess, $sess_apu_dl;
		parent::init();

		/* registger session variable if still isn't registered */
		if (!$sess->is_registered('sess_apu_dl')) $sess->register('sess_apu_dl');
		
		/* set default value for session variable */
		if (!isset($sess_apu_dl['filter'])){ 
			$sess_apu_dl['filter']['id'] = '';
			$sess_apu_dl['filter']['name'] = '';
			$sess_apu_dl['filter']['customer'] = '';
		}

		if (!isset($sess_apu_dl['act_row'])){ 
			$sess_apu_dl['act_row'] = 0;
		}

		if (isset($_GET['act_row'])) 
			$sess_apu_dl['act_row'] = $_GET['act_row'];
	}

	/**
	 *	set to search filter by $_POST params
	 */
	function set_filter_by_posts(){
		global $sess_apu_dl;

		/* show results from first row after form submit */
		$sess_apu_dl['act_row'] = 0;
		
		/* set search filter by values submited by form */
		$filter = &$sess_apu_dl['filter'];
	
		if (isset($_POST['dl_name'])) 		$filter['name']=$_POST['dl_name'];
		if (isset($_POST['dl_id']))   		$filter['id']=$_POST['dl_id'];
		if (isset($_POST['dl_customer']))	$filter['customer']=$_POST['dl_customer'];

		$this->f->load_defaults();
	}
	
	/**
	 *	Default action - get list of domains
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */
	function action_default(&$errors){
		global $data, $sess, $sess_apu_dl, $serweb_auth;

		$data->set_act_row($sess_apu_dl['act_row']);
		
		if (false === $this->domains = 
				$data->get_domains(
					array('filter' => $sess_apu_dl['filter'],
					      'get_domain_names' => true), 
					$errors)) 
			return false;

		$this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
		$this->pager['pos']=$data->get_act_row();
		$this->pager['items']=$data->get_num_rows();
		$this->pager['limit']=$data->get_showed_rows();
		$this->pager['from']=$data->get_res_from();
		$this->pager['to']=$data->get_res_to();

		foreach($this->domains as $key=>$val){
			$get = $this->controler->domain_to_get_param($val['id']);
			$this->domains[$key]['url_edit'] = $sess->url($this->opt['script_edit']."?kvrk=".uniqID("")."&".$get."&edit=1");
			$this->domains[$key]['url_enable'] = $sess->url($this->opt['script_edit']."?kvrk=".uniqID("")."&".$get."&enable_all=1");
			$this->domains[$key]['url_disable'] = $sess->url($this->opt['script_edit']."?kvrk=".uniqID("")."&".$get."&disable_all=1");
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
		global $sess_apu_dl;
		parent::create_html_form($errors);

		$filter = &$sess_apu_dl['filter'];

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"dl_name",
									 "size"=>11,
									 "maxlength"=>128,
		                             "value"=>$filter['name']));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"dl_id",
									 "size"=>11,
									 "maxlength"=>11,
		                             "value"=>$filter['id']));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"dl_customer",
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
	 *	assign variables to smarty 
	 */
	function pass_values_to_html(){
		global $smarty, $sess;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
		$smarty->assign_by_ref($this->opt['smarty_domains'], $this->domains);
		$smarty->assign($this->opt['smarty_url_new_domain'], $sess->url($this->opt['script_edit']."?kvrk=".uniqID("")."&new=1"));
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

<?php

/**
 * Application unit domain_list
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_domain_list.php,v 1.12 2007/12/13 11:36:06 kozlik Exp $
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
 *	'script_create'				(string) default: 'domain_edit.php'
 *	 name of script for create new_domain (which contain apu_domain)
 *
 *	'script_layout'				(string) default: 'domain_layout.php'
 *	 name of script which contain apu_domain_layout
 *
 *	'script_attributes'			(string) default: 'domain_attributes.php'
 *	 name of script for editing domain attributes
 *
 *	'only_domains'				(array)	default: null
 *	 Array of domain IDs. if is set, only domains from this array are returned
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

	var $filter=null;

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
		$this->opt['script_create'] =		'domain_edit.php';
		$this->opt['script_layout'] =		'domain_layout.php';
		$this->opt['script_attributes'] =	'domain_attributes.php';

		$this->opt['only_domains'] = null;

		
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

	function set_filter(&$filter){
		$this->filter = &$filter;
	}

	/**
	 *	this metod is called always at begining - initialize variables
	 */
	function init(){
		global $sess;
		parent::init();

		if (is_a($this->filter, "apu_base_class")){
			$this->filter->set_base_apu($this);
		}
	}

	function get_filter_form(){
		global $lang_str;
		
		$f = array();

		$f[] = array("type"=>"text",
		             "name"=>"name",
		             "maxlength"=>128,
					 "label"=>$lang_str['d_name']);

		$f[] = array("type"=>"text",
		             "name"=>"id",
		             "maxlength"=>64,
					 "label"=>$lang_str['d_id']);

		$f[] = array("type"=>"text",
		             "name"=>"customer",
		             "maxlength"=>128,
					 "label"=>$lang_str['owner']);

		return $f;
	}

	/**
	 *	Default action - get list of domains
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */
	function action_default(&$errors){
		global $data, $sess;

        $filter = array();
		if (is_a($this->filter, "apu_base_class")){
			$filter = $this->filter->get_filter();

    		$data->set_act_row($this->filter->get_act_row());
		}

		$opt = array('filter' => $filter,
					 'get_domain_names' => true,
					 'get_domain_flags' => true);
					 
		if (!is_null($this->opt['only_domains']))
			$opt['only_domains'] = $this->opt['only_domains'];
		
		if (false === $this->domains = 
				$data->get_domains(
					$opt, 
					$errors)) 
			return false;

		$this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
		$this->pager['pos']=$data->get_act_row();
		$this->pager['items']=$data->get_num_rows();
		$this->pager['limit']=$data->get_showed_rows();
		$this->pager['from']=$data->get_res_from();
		$this->pager['to']=$data->get_res_to();

		foreach($this->domains as $key=>$val){
			if ($val['id'] == '0') continue; //skip create urls for default domain
			
			$get = $this->controler->domain_to_get_param($val['id']);
			$this->domains[$key]['url_edit'] = $sess->url($this->opt['script_edit']."?kvrk=".uniqID("")."&".$get."&edit=1");
			$this->domains[$key]['url_enable'] = $sess->url($this->opt['script_edit']."?kvrk=".uniqID("")."&".$get."&enable=1");
			$this->domains[$key]['url_disable'] = $sess->url($this->opt['script_edit']."?kvrk=".uniqID("")."&".$get."&disable=1");
			$this->domains[$key]['url_dele'] = $sess->url($this->opt['script_edit']."?kvrk=".uniqID("")."&".$get."&delete=1");
			$this->domains[$key]['url_layout'] = $sess->url($this->opt['script_layout']."?kvrk=".uniqID("")."&".$get);
			$this->domains[$key]['url_attributes'] = $sess->url($this->opt['script_attributes']."?kvrk=".uniqID("")."&".$get);
		}

		return true;
	}
	
	/**
	 *	check _get and _post arrays and determine what we will do 
	 */
	function determine_action(){
		$this->action=array('action'=>"default",
		                    'validate_form'=>false,
						    'reload'=>false);
	}
	
	/**
	 *	assign variables to smarty 
	 */
	function pass_values_to_html(){
		global $smarty, $sess;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
		$smarty->assign_by_ref($this->opt['smarty_domains'], $this->domains);
		$smarty->assign($this->opt['smarty_url_new_domain'], $sess->url($this->opt['script_create']."?kvrk=".uniqID("")."&new=1"));
	}
	
}

?>

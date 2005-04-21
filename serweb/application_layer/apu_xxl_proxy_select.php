<?php

/*
 * Application unit xxl_proxy_select
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_xxl_proxy_select.php,v 1.2 2005/04/21 15:09:45 kozlik Exp $
 * @package   serweb
 */ 

/* Application unit xxl_proxy_select
 *
 *
 *	This application unit is used for select proxy when xxl version is used
 *	   
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
 */

class apu_xxl_proxy_select extends apu_base_class{
	var $smarty_action='default';
	var $proxies=array();

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_proxies_xxl');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_xxl_proxy_select(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		

		/* message on attributes update */
		$this->opt['msg_change']['short'] =	&$lang_str['msg_xxl_proxy_changed_s'];
		$this->opt['msg_change']['long']  =	&$lang_str['msg_xxl_proxy_changed_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
			/* name of html form */
		$this->opt['form_name'] =			'';
		
		
	}

	function action_update(&$errors){
		global $sess_xxl_selected_proxy;
		$sess_xxl_selected_proxy = $this->proxies[$_POST['ps_proxy']];
	
		return array("m_ps_changed=".RawURLEncode($this->opt['instance_id']));
	}
	
	/* this metod is called always at begining */
	function init(){
		parent::init();
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			$this->action=array('action'=>"update",
			                    'validate_form'=>true,
								'reload'=>true);
		}
		else $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
	}
	
	/* create html form */
	function create_html_form(&$errors){
		global $data, $sess_xxl_selected_proxy;
		parent::create_html_form($errors);

		if (false === $this->proxies = $data->get_proxies_xxl(array('sort' => true), $errors)) return false;

		$selected = 0;
		$options = array();
		foreach($this->proxies as $k=>$v){
			$options[] = array('label'=>$v['proxy'], 'value'=>$k);
			if ($v['proxy'] == $sess_xxl_selected_proxy['proxy']) $selected = $k;
		}

		$this->f->add_element(array("type"=>"select",
		                             "name"=>"ps_proxy",
									 "size"=>1,
									 "options" => $options,
									 "value" => $selected));

	}

	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;
		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_ps_changed']) and $_GET['m_ps_changed'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_change'];
			$this->smarty_action="was_changed";
		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => '',
					 'before'      => '');
	}
}


?>

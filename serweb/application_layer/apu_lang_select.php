<?php
/*
 * Application unit lang selector
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_lang_select.php,v 1.5 2005/12/14 16:40:04 kozlik Exp $
 * @package   serweb
 */ 

/* Application unit lang selector
 *
 *
 *	This application unit is used for select language of serweb
 *	   
 *	Configuration:
 *	--------------
 *	'use_charset_only'			(string) default: 'utf-8'
 *	 display only languages that exist in specified charset. Set to empty to
 *	 show all language setting form $avaiable_languages array
 *	
 *	'save_to_user_attr'			(bool) default: false
 *	 If true, selected language is saved into user_attrs on submit
 *	 The name of user_attr is defined by config variable $config->attr_names['lang']
 *
 *	'save_to_cookie'			(bool) default: true
 *	 If true, selected language is saved into cookie on submit
 *
 *	'msg_update'				default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
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

class apu_lang_select extends apu_base_class{
	var $smarty_action='default';

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('update_attribute');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_lang_select(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['use_charset_only'] =	'utf-8';
		$this->opt['save_to_user_attr']      =	false;
		$this->opt['save_to_cookie']      	 =	true;


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

	/* this metod is called always at begining */
	function init(){
		parent::init();
	}
	
	function action_update(&$errors){
		global $available_languages, $config;
		
		$_SESSION['lang'] = $_POST['ls_language'];

		$opt = array("uid" => $this->user_id->uuid);

		if ($this->opt['save_to_cookie']){
			setcookie('serweb_lang', $_SESSION['lang'], time()+31536000, $config->root_path);
		}
		
		if ($this->opt['save_to_user_attr']){
			$an = &$config->attr_names;
		
			$attrs = &User_Attrs::singleton($this->controler->user_id->uuid);
			if (false === $attrs->set_attribute($an['lang'], 
			                                    $available_languages[$_SESSION['lang']][2])) 
				return false;
		}

		return array("m_ls_updated=".RawURLEncode($this->opt['instance_id']));
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
		global $available_languages;
		parent::create_html_form($errors);

		$options = array();
		
	    foreach($available_languages AS $id => $tmplang) {
	    	/* skip entries with charset different from $this->opt['use_charset_only'] */
			if ($this->opt['use_charset_only'] and 
				false === strpos($id, $this->opt['use_charset_only'])){
				
				continue;
			}
	    
	    	$options[]= array("label" => ucfirst(substr(strrchr($tmplang[0], '|'), 1)).
			                                    ($this->opt['use_charset_only']?
											         "":
													 (" (".$id.")")),
							  "value" => $id);
	    } 		
	    
		uasort($options, create_function(
			'&$a, $b', 'return (strcmp($a["label"], $b["label"]));'));


		$this->f->add_element(array("type"=>"select",
									 "name"=>"ls_language",
									 "options"=>$options,
									 "size"=>1,
		                             "value"=>$_SESSION['lang']));

	}

	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;
		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_ls_updated']) and $_GET['m_ls_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
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

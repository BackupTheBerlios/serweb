<?php
/**
 * Application unit whitepages
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_whitepages.php,v 1.1 2005/04/21 15:09:45 kozlik Exp $
 * @package   serweb
 */ 

/** Application unit whitepages 
 *
 *
 *	This application unit is used for find user
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
 *	opt['smarty_pager']			(pager)
 *		associative array containing size of result and which page is returned
 *
 *	opt['smarty_users']				(found_users)
 *		associative array containing founded users 
 *		The array have same keys as function find_users (from data layer) returned. 
 *
 *	@todo: opt['get_user_status'], opt['get_user_aliases']
 *	
 */

class apu_whitepages extends apu_base_class{
	var $smarty_action='default';
	var $pager = array();
	var $found_users=array();
	var $form_submited = false;
	var $js_before;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('find_users');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array('sip_address_completion.js.php');
	}
	
	/* constructor */
	function apu_whitepages(){
		global $lang_str, $sess_lang;
		parent::apu_base_class();

		/* set default values to $this->opt */		

		$this->opt['phonebook'] =					"phonebook.php";


		/* message on attributes update */
		$this->opt['msg_update']['short'] =	&$lang_str['msg_changes_saved_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_changes_saved_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* pager */
		$this->opt['smarty_pager'] =		'pager';

		$this->opt['smarty_users'] =		'found_users';

		/* name of html form */
		$this->opt['form_name'] =			'';

		$this->opt['form_submit']=array('type' => 'image',
										'text' => $lang_str['b_find'],
										'src'  => get_path_to_buttons("btn_find.gif", $sess_lang));
		
	}

	function action_default(&$errors){
		global $data, $sess_wp_search_filter, $sess;
		
		if ($this->form_submited){
			$sess_wp_search_filter['act_row'] = 0;
			if (isset($_POST['wp_fname']))	 $sess_wp_search_filter['fname'] = $_POST['wp_fname'];
			if (isset($_POST['wp_lname']))	 $sess_wp_search_filter['lname'] = $_POST['wp_lname'];
			if (isset($_POST['wp_uname']))	 $sess_wp_search_filter['uname'] = $_POST['wp_uname'];
			if (isset($_POST['wp_sip_uri'])) $sess_wp_search_filter['sip_uri'] = $_POST['wp_sip_uri'];
			if (isset($_POST['wp_alias']))	 $sess_wp_search_filter['alias'] = $_POST['wp_alias'];
		
			if (isset($_POST['wp_onlineonly'])) $sess_wp_search_filter['onlineonly'] = '1';
			else $sess_wp_search_filter['onlineonly'] = '0';
		}
		
		$data->set_act_row($sess_wp_search_filter['act_row']);
		if(false === $this->found_users = $data->find_users($sess_wp_search_filter, $errors)) return false;

		$this->pager['url']		= $_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
		$this->pager['pos']		= $data->get_act_row();
		$this->pager['items']	= $data->get_num_rows();
		$this->pager['limit']	= $data->get_showed_rows();
		$this->pager['from']	= $data->get_res_from();
		$this->pager['to']		= $data->get_res_to();

		foreach($this->found_users as $key=>$val){
			$this->found_users[$key]['url_add'] = $sess->url($this->opt['phonebook']."?kvrk=".uniqID("").
																	"&add_from_wp=1".
																	"&fname=".RawURLEncode($val['fname']).
																	"&lname=".RawURLEncode($val['lname']).
																	"&sip_uri=".RawURLEncode($val['sip_uri']));
		}


		return true;
	}
	
	/* this metod is called always at begining */
	function init(){
		global $sess, $sess_wp_search_filter;
		parent::init();

		if (!$sess->is_registered('sess_wp_search_filter')) $sess->register('sess_wp_search_filter');
		if (!isset($sess_wp_search_filter)) {
			$sess_wp_search_filter=array();
			$sess_wp_search_filter['act_row'] = 0;
			$sess_wp_search_filter['fname'] = '';
			$sess_wp_search_filter['lname'] = '';
			$sess_wp_search_filter['uname'] = '';
			$sess_wp_search_filter['sip_uri'] = '';
			$sess_wp_search_filter['alias'] = '';
			$sess_wp_search_filter['onlineonly'] = '';
		}

		if (isset($_GET['act_row'])) $sess_wp_search_filter['act_row'] = $_GET['act_row'];

	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			$this->form_submited = true;
		}
		
		$this->action=array('action'=>"default",
		                     'validate_form' => $this->form_submited,
							 'reload'=>false);
	}
	
	/* create html form */
	function create_html_form(&$errors){
		global $sess_wp_search_filter, $lang_str;
		parent::create_html_form($errors);

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"wp_fname",
									 "size"=>16,
									 "maxlength"=>32,
		                             "value"=>isset($_POST['wp_fname'])?$_POST['wp_fname']:$sess_wp_search_filter['fname']));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"wp_lname",
									 "size"=>16,
									 "maxlength"=>32,
		                             "value"=>isset($_POST['wp_lname'])?$_POST['wp_lname']:$sess_wp_search_filter['lname']));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"wp_uname",
									 "size"=>16,
									 "maxlength"=>64,
		                             "value"=>isset($_POST['wp_uname'])?$_POST['wp_uname']:$sess_wp_search_filter['uname']));



		$this->f->add_element(array("type"=>"text",
		                             "name"=>"wp_sip_uri",
									 "size"=>16,
									 "maxlength"=>128,
									 "value" => isset($_POST['wp_sip_uri'])?$_POST['wp_sip_uri']:$sess_wp_search_filter['sip_uri'],
									 "valid_regex" => "^(".$this->controler->reg->sip_address.")?$",
									 "valid_e" => $lang_str['fe_not_valid_sip'],
									 "extrahtml" => "onBlur='sip_address_completion(this)'"));
	
		$this->js_before .= 'sip_address_completion(f.wp_sip_uri);';



		$this->f->add_element(array("type"=>"text",
		                             "name"=>"wp_alias",
									 "size"=>16,
									 "maxlength"=>64,
		                             "value"=>isset($_POST['wp_alias'])?$_POST['wp_alias']:$sess_wp_search_filter['alias']));
		$this->f->add_element(array("type"=>"checkbox",
		                             "value"=>1,
									 "checked"=>$this->was_form_submited()?
									 				(isset($_POST['wp_onlineonly'])?true:false):
													$sess_wp_search_filter['onlineonly'],
		                             "name"=>"wp_onlineonly"));
	}

	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;
		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
//		if (isset($_GET['m_my_apu_updated']) and $_GET['m_my_apu_updated'] == $this->opt['instance_id']){
//			$msgs[]=&$this->opt['msg_update'];
//			$this->smarty_action="was_updated";
//		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
//		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);

		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
		$smarty->assign_by_ref($this->opt['smarty_users'], $this->found_users);
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => '',
					 'before'      => $this->js_before);
	}
}


?>

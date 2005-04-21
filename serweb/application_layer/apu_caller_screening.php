<?php
/**
 * Application unit caller_screening 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_caller_screening.php,v 1.1 2005/04/21 15:09:45 kozlik Exp $
 * @package   serweb
 */ 

/**
 *  Application unit caller_screening 
 *
 *	This application unit is used for manipulate with caller screening
 *	   
 *	Configuration:
 *	--------------
 *	
 *	'msg_add'					default: $lang_str['msg_caller_screening_added_s'] and $lang_str['msg_caller_screening_added_l']
 *	 message which should be showed on add screened uri - assoc array with keys 'short' and 'long'
 *	
 *	'msg_update'				default: $lang_str['msg_caller_screening_updated_s'] and $lang_str['msg_caller_screening_updated_l']
 *	 message which should be showed on screened uri update - assoc array with keys 'short' and 'long'
 *	
 *	'msg_delete'				default: $lang_str['msg_caller_screening_deleted_s'] and $lang_str['msg_caller_screening_deleted_l']
 *	 message which should be showed on screened uri delete - assoc array with keys 'short' and 'long'
 *	
 *	
 *	'require_acknowledge_of_delete' 	(bool) default: false
 *	 set to true if should be displayed confirmation page on deleting screened uri
 *	 This option need to be supported in templates. For acknowledge deletion
 *	 must be submited form of this APU by default submit element.  Submiting by
 *	 anything else submit element cause cancel deletion.
 *	
 *	'screening_variants'		(array)
 *	 array of objects Ccall_fw
 *	 this array describe how to dispose of draggers
 *	 You can create elements of array by:
 *	 	new Ccall_fw(<action>, <param1>, <param2>, <label>)
 *	 where:
 *		<action> is "reply" or "relay"
 *		"reply" have parameters status code and phrase (e.g. ("486", "busy") or ("603", "decline"))
 *		"relay" have only one parameter - address of server where to request forward
 *		<label> is string which is displayed to user
 *
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
 *	'smarty_cs'					name of smarty variable - see below
 *	'smarty_cs_entry'			name of smarty variable - see below
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
 *	  'was_added'   - when user submited form and new screened uri was succefully stored
 *	  'was_deleted' - when user delete screened uri
 *	  'edit'        - when user is editing screened uri
 *	  'delete_ack'  - when delete acknowledment is required
 *	
 *	opt['smarty_pager']			(pager)
 *	 associative array containing size of result and which page is returned
 *	
 *	opt['smarty_cs'] 			(cs)	
 *	 associative array containing screened uris of user
 *	 The array have same keys as function get_cs_callers (from data layer) returned. 
 *	
 *	opt['smarty_cs_entry'] 		(cs_entry)	
 *	 associative array containing info about current screened uri
 *	 The array have same keys as function get_cs_caller (from data layer) returned. 
 *	
 */

class apu_caller_screening extends apu_base_class{
	var $smarty_action='default';
	var $act_cs_id = "";			//id of current entry
	var $pager = array();
	var $cs = array();
	var $cs_entry = array();		//assoc - current screened uri

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('del_cs_caller', 'get_cs_caller', 'update_cs_caller', 'get_cs_callers');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_caller_screening(){
		global $lang_str, $config;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		
		$this->opt['screening_variants'] = array();
		$this->opt['screening_variants'][]=new Ccall_fw("reply", "603", "decline", $lang_str['cs_decline']);
		$this->opt['screening_variants'][]=new Ccall_fw("reply", "486", "busy", $lang_str['cs_reply_busy']);
		$this->opt['screening_variants'][]=new Ccall_fw("relay", "sip:voicemail@".$config->domain, null, $lang_str['cs_fw_to_voicemail']);


		$this->opt['require_acknowledge_of_delete'] = 	false;


		/* message on attributes update */
		$this->opt['msg_add']['short'] =	&$lang_str['msg_caller_screening_added_s'];
		$this->opt['msg_add']['long']  =	&$lang_str['msg_caller_screening_added_l'];

		$this->opt['msg_update']['short'] =	&$lang_str['msg_caller_screening_updated_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_caller_screening_updated_l'];

		$this->opt['msg_delete']['short'] =	&$lang_str['msg_caller_screening_deleted_s'];
		$this->opt['msg_delete']['long']  =	&$lang_str['msg_caller_screening_deleted_l'];

		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* pager */
		$this->opt['smarty_pager'] =		'pager';

		$this->opt['smarty_cs'] =			'cs';

		$this->opt['smarty_cs_entry'] = 	'cs_entry';
		
		/* name of html form */
		$this->opt['form_name'] =			'';
		
	}

	function get_cs(&$errors){
		global $data, $sess_cs_act_row, $sess;
		
		$data->set_act_row($sess_cs_act_row);

		$opt = array();
					 
		if ($this->action['action'] == 'edit')
			$opt['csid'] = $this->act_cs_id;

		if (false === $this->cs = $data->get_cs_callers($this->user_id, $opt, $errors)) return false;

		$this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
		$this->pager['pos']=$data->get_act_row();
		$this->pager['items']=$data->get_num_rows();
		$this->pager['limit']=$data->get_showed_rows();
		$this->pager['from']=$data->get_res_from();
		$this->pager['to']=$data->get_res_to();

		foreach($this->cs as $key=>$val){
			$this->cs[$key]['label'] 	  = Ccall_fw::get_label($this->opt['screening_variants'], $val['action'], $val['param1'], $val['param2']);
			$this->cs[$key]['url_dele'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&cs_dele_id=".$val['id']);
			$this->cs[$key]['url_edit'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&cs_edit_id=".$val['id']);
		}
		
		return true;
	}


	function action_add(&$errors){
		global $data;
		
		if (!$data->update_cs_caller($this->user_id, null, 
							$_POST['cs_uri_re'], 
							$this->opt['screening_variants'][$_POST['cs_action_key']]->action,
							$this->opt['screening_variants'][$_POST['cs_action_key']]->param1,
							$this->opt['screening_variants'][$_POST['cs_action_key']]->param2,
							$errors)) 
			return false;

		return array("m_cs_added=".RawURLEncode($this->opt['instance_id']));
	}

	function action_update(&$errors){
		global $data;

		if (!$data->update_cs_caller($this->user_id, $this->act_cs_id, 
							$_POST['cs_uri_re'], 
							$this->opt['screening_variants'][$_POST['cs_action_key']]->action,
							$this->opt['screening_variants'][$_POST['cs_action_key']]->param1,
							$this->opt['screening_variants'][$_POST['cs_action_key']]->param2,
							$errors)) 
			return false;

		return array("m_cs_updated=".RawURLEncode($this->opt['instance_id']));
	}

	function action_delete(&$errors){
		global $data;

		if (!$data->del_CS_caller($this->user_id, $this->act_cs_id, $errors)) return false;
	
		return array("m_cs_deleted=".RawURLEncode($this->opt['instance_id']));
	}


	function action_default(&$errors){
		if (false === $this->get_cs($errors)) return false;
	}

	function action_edit(&$errors){
		if (false === $this->get_cs($errors)) return false;
		$this->smarty_action="edit";
	}

	function action_delete_ack(&$errors){
		if (false === $this->get_cs($errors)) return false;
		$this->smarty_action="delete_ack";
	}

	
	/* this metod is called always at begining */
	function init(){
		global $sess_cs_act_row, $sess;
		parent::init();

		if (!$sess->is_registered('sess_cs_act_row')) $sess->register('sess_cs_act_row');
		if (!isset($sess_cs_act_row)) $sess_cs_act_row=0;
		
		if (isset($_GET['act_row'])) $sess_cs_act_row=$_GET['act_row'];
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		$action_delete = array('action'=>"delete",
				                    'validate_form'=>false,
									'reload'=>true);

		if ($this->was_form_submited()){	// Is there data to process?
			if (isset($_REQUEST['cs_delete_ack'])){
				$this->act_cs_id = $_REQUEST['cs_delete_ack'];
				$this->action = $action_delete;
				return;
			}

			if ($_REQUEST['cs_id']){
				$this->act_cs_id = $_REQUEST['cs_id'];
				$this->action = array('action'=>"update",
			                    'validate_form'=>true,
								'reload'=>true);
			}
			else {
				$this->action = array('action'=>"add",
			                    'validate_form'=>true,
								'reload'=>true);
			}
			return;
		}
		
		if (isset($_GET['cs_edit_id'])){
			$this->act_cs_id = $_GET['cs_edit_id'];
			$this->action = array('action'=>"edit",
			                    'validate_form'=>false,
								'reload'=>false);
			return;
		}

		if (isset($_GET['cs_dele_id'])){
			$this->act_cs_id = $_GET['cs_dele_id'];

			if ($this->opt['require_acknowledge_of_delete']){
				$this->action = array('action'=>"delete_ack",
				                    'validate_form'=>false,
									'reload'=>false);
				return;
			}
			else {
				$this->action = $action_delete;
				return;
			}
		}

		
		$this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
	}
	
	/* create html form */
	function create_html_form(&$errors){
		global $data, $config, $lang_str;
		parent::create_html_form($errors);

		if ($this->action['action'] == 'edit' or $this->action['action'] == 'delete_ack'){
			if (false === $this->cs_entry = $data->get_CS_caller($this->user_id, $this->act_cs_id, $errors)) return false;
		}


		//create array of options of select
		$opt=array();
		foreach($this->opt['screening_variants'] as $k => $v){
			$opt[]=array("label" => $v->label, "value" => $k);
		}
	
		if ($this->action['action'] == 'delete_ack'){
			$this->f->add_element(array("type"=>"hidden",
			                             "name"=>"cs_delete_ack",
			                             "value"=>$this->act_cs_id));
		}
		else {
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"cs_uri_re",
										 "size"=>16,
										 "maxlength"=>128,
			                             "value"=>isset($this->cs_entry->uri_re)?$this->cs_entry->uri_re:"",
										 "minlength"=>1,
										 "length_e"=>$lang_str['fe_not_caller_uri']));
										 
			$this->f->add_element(array("type"=>"select",
			                             "name"=>"cs_action_key",
										 "size"=>1,
			                             "value"=>isset($this->cs_entry->action)?(
										 			Ccall_fw::get_key($this->opt['screening_variants'],
																		$this->cs_entry->action,
																		$this->cs_entry->param1,
																		$this->cs_entry->param2)
													):"",
										 "options"=>$opt));
	
			$this->f->add_element(array("type"=>"hidden",
			                             "name"=>"cs_id",
			                             "value"=>$this->act_cs_id));
		}
	}

	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;
		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_cs_updated']) and $_GET['m_cs_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}

		if (isset($_GET['m_cs_added']) and $_GET['m_cs_added'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_add'];
			$this->smarty_action="was_added";
		}

		if (isset($_GET['m_cs_deleted']) and $_GET['m_cs_deleted'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_delete'];
			$this->smarty_action="was_deleted";
		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
		$smarty->assign_by_ref($this->opt['smarty_cs'], $this->cs);
		$smarty->assign_by_ref($this->opt['smarty_cs_entry'], $this->cs_entry);
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

<?
/**
 * Application unit accounting 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_accounting.php,v 1.1 2005/08/23 10:33:10 kozlik Exp $
 * @package   serweb
 */ 

/** Application unit accounting 
 *
 *
 *	This application unit is used for view incoming and outgoing calls of user
 *	
 *	Configuration:
 *	--------------
 *	'use_filter'				(bool) default: false
 *	 if should be used filter for displaying incoming/outgoing/all calls
 *	 
 *	'filter_labels'	 		(associative array)
 *	 text labels for filter select element. 
 *	 Keys of array: all, incoming, outgoing
 *	
 *	'display_incoming'		(bool) default: true
 *	 have effect only when 'use_filter' is false
 *	 if is true, incoming calls are displayed
 *	
 *	 notice: working only if users are indexed by UUID 
 *	         '$config->users_indexed_by' option in config_data_layer.php
 *
 *	'display_outgoing'		(bool) default: true
 *	 have effect only when 'use_filter' is false
 *	 if is true, outgoing calls are displayed
 *
 *	'display_missed'		(bool) default: true
 *	 have effect only when 'use_filter' is false
 *	 if is true, missed calls are displayed
 *	
 *	
 *	'get_user_status'		(bool) default: false
 *	  should output array contain status of user?
 *	   
 *	'get_phonebook_names'	(bool) default: false
 *	 should output contain names from phonebook?
 *	
 *	'convert_sip_addresses_to_phonenumbers'	(bool) default: false
 *	 true if numerical username part of sip address from our domain should be 
 *	 displayed without domain and initial 'sip:'
 *	
 *	'cvs_export_template'	(string) default: 'acc_cvs.tpl'
 *	 filename of smarty template for output in CSV format
 *	
 *	'msg_delete'					default: $lang_str['msg_calls_deleted_s'] and $lang_str['msg_calls_deleted_l']
 *	 message which should be showed on calls delete - assoc array with keys 'short' and 'long'
 *	
 *	'form_name'				(string) default: ''
 *		name of html form
 *	
 *	'smarty_url_delete'			name of smarty variable - see below
 *	'smarty_url_cvs_export'		name of smarty variable - see below
 *	'smarty_result'				name of smarty variable - see below
 *	'smarty_form'				name of smarty variable - see below
 *	'smarty_action'				name of smarty variable - see below
 *	'smarty_pager'				name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_url_delete']		(url_delete)
 *	 url for delete acc records of user
 *	
 *	opt['smarty_url_cvs_export'] (url_export)
 *	 url for get acc records in CSV format
 *	
 *	opt['smarty_form']			(form)
 *	 phplib html form
 *	 
 *	opt['smarty_action']			(action)
 *	 tells what should smarty display. Values:
 *		'default' - 
 *	
 *	opt['smarty_result'] 		(acc_res)	
 *	 associative array containing accounting informations 
 *	 The array have same keys as function get_acc_entries (from data layer) returned. 
 *	 If convert_sip_addresses_to_phonenumbers is true, array extra contains key sip_address
 *	 
 *	opt['smarty_pager'] 			(pager)		
 *	 associative array containing size of result and which page is returned
 */
 
class apu_accounting extends apu_base_class{
	var $smarty_action='default';
	var $acc_res;
	var $pager=array();
	var $reg;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		global $config;

		if ($config->acc_use_cdr_table){
			return array('get_cdr_entries', 'delete_user_acc', 'delete_user_missed_calls');
		}
		else{
			return array('get_acc_entries', 'delete_user_acc', 'delete_user_missed_calls');
		}
	}

	/* return array of strings - required javascript files */
	function get_required_javascript(){
		return array("click_to_dial.js.php");
	}
	
	function apu_accounting(){
		global $lang_str;
		parent::apu_base_class();

		$this->opt['use_filter'] =	false;
		$this->opt['filter_labels'] = array('all' => $lang_str['sel_item_all_calls'],
		                                    'incoming' => $lang_str['sel_item_incoming_cals'],
											'outgoing' => $lang_str['sel_item_outgoing_calls']);

		$this->opt['display_incoming'] =	true;
		$this->opt['display_outgoing'] =	true;
		$this->opt['display_missed'] =		true;
											
		$this->opt['get_user_status'] = false;
		$this->opt['get_phonebook_names'] = false;
		$this->opt['convert_sip_addresses_to_phonenumbers'] = false;

		$this->opt['cvs_export_template'] =	'acc_cvs.tpl';

		$this->opt['msg_delete']['short'] =	&$lang_str['msg_calls_deleted_s'];
		$this->opt['msg_delete']['long']  =	&$lang_str['msg_calls_deleted_l'];

		
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';

		
		/*** names of variables assigned to smarty ***/
		/* array containing accounting table */
		$this->opt['smarty_result'] =	'acc_res';
		/* pager */
		$this->opt['smarty_pager'] =	'pager';

		$this->opt['smarty_url_delete'] =	'url_delete';
		$this->opt['smarty_url_cvs_export'] =	'url_export';
	}

	/* this metod is called always at begining */
	function init(){
		global $sess, $sess_acc_act_row, $sess_acc_filter;
		parent::init();
		
		if (!$sess->is_registered('sess_acc_act_row')) $sess->register('sess_acc_act_row');
		if (!$sess->is_registered('sess_acc_filter')) $sess->register('sess_acc_filter');
		if (!isset($sess_acc_act_row)) $sess_acc_act_row=0;
		if (!isset($sess_acc_filter)) $sess_acc_filter='all';
		
		if (isset($_GET['act_row'])) $sess_acc_act_row=$_GET['act_row'];
						 
		$this->acc_res=array();
		
		$this->reg = new Creg();
	}

	function prepare_opt_param_for_get_acc_entries(){
		global $sess_acc_filter;

		$opt=array('get_phonebook_names' => $this->opt['get_phonebook_names'],
		           'get_user_status' => $this->opt['get_user_status']);
		
		if ($this->opt['use_filter']){
			switch ($sess_acc_filter){
			case 'incoming':
				$opt['filter_outgoing'] = false and $this->opt['display_outgoing'];
				$opt['filter_incoming'] = true  and $this->opt['display_incoming'];
				$opt['filter_missed']   = true  and $this->opt['display_missed'];
				break;
			case 'outgoing':
				$opt['filter_outgoing'] = true  and $this->opt['display_outgoing'];
				$opt['filter_incoming'] = false and $this->opt['display_incoming'];
				$opt['filter_missed']   = false and $this->opt['display_missed'];
				break;
			case 'all':
			default:
				$opt['filter_outgoing'] = true and $this->opt['display_outgoing'];
				$opt['filter_incoming'] = true and $this->opt['display_incoming'];
				$opt['filter_missed']   = true and $this->opt['display_missed'];
				break;
			}
		}
		else {
			$opt['filter_outgoing'] = $this->opt['display_outgoing'];
			$opt['filter_incoming'] = $this->opt['display_incoming'];
			$opt['filter_missed']   = $this->opt['display_missed'];
		}
		
		return $opt;
	}
	
	function convert_sip_addresses_to_phonenumbers(){
		global $config;
		
		foreach($this->acc_res as $key => $val){
			$uname = $this->reg->get_username($val['to_uri']);
			$domain = $this->reg->get_domainname($val['to_uri']);
			if (ereg($this->reg->phonenumber_strict, $uname) and $domain == $config->domain) 
				$this->acc_res[$key]['sip_address'] = $uname;
			else $this->acc_res[$key]['sip_address'] = $val['to_uri'];
		}
	}
	
	/* realize action */
	function action_default(&$errors){
		global $data, $sess_acc_act_row;
		
		do{
			$this->controler->set_timezone();
			$data->set_act_row($sess_acc_act_row);
			
			$opt = $this->prepare_opt_param_for_get_acc_entries();
			
			if (false === $this->acc_res = $data->get_acc_entries($this->user_id, $opt, $errors)) return false;

			$this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
			$this->pager['pos']=$data->get_act_row();
			$this->pager['items']=$data->get_num_rows();
			$this->pager['limit']=$data->get_showed_rows();
			$this->pager['from']=$data->get_res_from();
			$this->pager['to']=$data->get_res_to();
			
			if ($this->opt['convert_sip_addresses_to_phonenumbers'])
				$this->convert_sip_addresses_to_phonenumbers();
			
		}while (false);
	}

	function action_export(&$errors){
		global $data, $smarty;

		$this->controler->set_timezone();
			
		$opt = $this->prepare_opt_param_for_get_acc_entries();
		$opt['limit_query'] = false;
		
		if ($config->acc_use_cdr_table){
			if (false === $this->acc_res = $data->get_cdr_entries($this->user_id, $opt, $errors)) return false;
		}
		else {
			if (false === $this->acc_res = $data->get_acc_entries($this->user_id, $opt, $errors)) return false;
		}

		if ($this->opt['convert_sip_addresses_to_phonenumbers'])
			$this->convert_sip_addresses_to_phonenumbers();

		header("Content-Type: text/plain\n");
		header("Content-Transfer-Encoding: 8bit\n");
		Header("Content-Disposition: attachment;filename=calls.csv");
		
		$smarty->assign_by_ref($this->opt['smarty_result'], $this->acc_res);
		$smarty->display($this->opt['cvs_export_template']);

		page_close();
		exit;
	}

	function action_delete(&$errors){
		global $data, $config;
		
		$opt = array();
		if (isset($_GET['timestamp'])) $opt['timestamp'] = $_GET['timestamp'];
		
		if ($this->opt['display_incoming'] or $this->opt['display_outgoing']){
			$opt['del_incoming'] = $this->opt['display_incoming'];
			$opt['del_outgoing'] = $this->opt['display_outgoing'];
			if (false === $data->delete_user_acc($this->user_id, $opt, $errors)) return false;
		}
		
		/* if missed calls are displayed, delete its too */
		if ($this->opt['display_missed']){
			if (!$opt['timestamp']) $opt['timestamp']=null;
			if (false === $data->delete_user_missed_calls($this->user_id, $opt['timestamp'], $errors)) return false;
		} 

		return array("m_acc_calls_deleted=".RawURLEncode($this->opt['instance_id']));
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		global $sess_acc_filter, $sess_acc_act_row;
		
		if (isset($_GET['acc_delete']) and $_GET['acc_delete'] == $this->opt['instance_id']){

			$this->action=array('action'=>"delete",
			                    'validate_form'=>false,
			                    'reload'=>true);
			return;		
		}

		if (isset($_GET['acc_export']) and $_GET['acc_export'] == $this->opt['instance_id']){

			$this->action=array('action'=>"export",
			                    'validate_form'=>false,
			                    'reload'=>false,
								'alone'=>true);
			return;		
		}
		
		if ($this->was_form_submited()){	// Is there data to process?
			$sess_acc_filter = $_POST['filter'];
			$sess_acc_act_row = 0;
		}

		$this->action=array('action'=>"default",
		                    'validate_form'=>false,
		                    'reload'=>false);
	}

	/* create html form */
	function create_html_form(&$errors){
		global $sess_acc_filter;
		parent::create_html_form($errors);

		if ($this->opt['use_filter']){
			$opt = array(
					array('label' => $this->opt['filter_labels']['all'], 'value' => 'all'),
					array('label' => $this->opt['filter_labels']['incoming'], 'value' => 'incoming'),
					array('label' => $this->opt['filter_labels']['outgoing'], 'value' => 'outgoing')
					);
		
			$this->f->add_element(array("type"=>"select",
			                             "name"=>"filter",
										 "size"=>1,
			                             "value"=>$sess_acc_filter,
										 "options"=>$opt));
		}						 

	}


	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_acc_calls_deleted']) and $_GET['m_acc_calls_deleted'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_delete'];
			$this->smarty_action="was_deleted";
		}

	}
	
	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty, $sess;

		if(!$this->acc_res) $this->acc_res = array();
		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
		$smarty->assign_by_ref($this->opt['smarty_result'], $this->acc_res);

		$smarty->assign($this->opt['smarty_url_delete'], 
					$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
							"&acc_delete=".RawURLEncode($this->opt['instance_id']).
							"&timestamp=".time()));

		$smarty->assign($this->opt['smarty_url_cvs_export'], 
					$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
							"&acc_export=".RawURLEncode($this->opt['instance_id'])));
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

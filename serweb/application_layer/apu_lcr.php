<?php
/*
 * Application unit lcr 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_lcr.php,v 1.2 2005/04/21 15:09:45 kozlik Exp $
 * @package   serweb
 */ 

/* Application unit lcr
 *
 *
 *	This application unit is used for view lcr table
 *	   
 *	Configuration:
 *	--------------
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

class apu_lcr extends apu_base_class{
	var $smarty_action='default';
	var $pager = array();
	var $lcr = array();		//array containing whole lcr

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_lcr_entries');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_lcr(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		

		
		/*** names of variables assigned to smarty ***/
		/* pager */
		$this->opt['smarty_pager'] =		'pager';

		$this->opt['smarty_lcr'] =			'lcr';
		
		
	}

	function action_default(&$errors){
		global $data, $sess_lcr_act_row;
		
		$data->set_act_row($sess_lcr_act_row);
		$opt = array('limit' => true);
		
		if (false === $this->lcr=$data->get_lcr_entries($opt, $errors)) return false;

		$this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
		$this->pager['pos']=$data->get_act_row();
		$this->pager['items']=$data->get_num_rows();
		$this->pager['limit']=$data->get_showed_rows();
		$this->pager['from']=$data->get_res_from();
		$this->pager['to']=$data->get_res_to();


		return true;
	}
	
	/* this metod is called always at begining */
	function init(){
		global $sess, $sess_lcr_act_row;
		parent::init();
		
		if (!$sess->is_registered('sess_lcr_act_row')) $sess->register('sess_lcr_act_row');
		if (!isset($sess_lcr_act_row)) $sess_lcr_act_row=0;
		
		if (isset($_GET['act_row'])) $sess_lcr_act_row=$_GET['act_row'];

	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		$this->action=array('action'=>"default",
		                     'validate_form'=>false,
							 'reload'=>false);
	}
	
	

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;

		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
		$smarty->assign_by_ref($this->opt['smarty_lcr'], $this->lcr);
	}
	
}


?>

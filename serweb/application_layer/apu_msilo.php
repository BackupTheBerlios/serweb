<?php
/**
 * Application unit msilo 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_msilo.php,v 1.1 2005/04/21 15:09:45 kozlik Exp $
 * @package   serweb
 */ 


/** Application unit msilo 
 *
 *
 *	This application unit is used for reading and deleting stored instant messages
 *	   
 *	Configuration:
 *	--------------
 *	
 *	'msg_delete'					default: $lang_str['msg_im_deleted_s'] and $lang_str['msg_im_deleted_l']
 *	 message which should be showed on message delete - assoc array with keys 'short' and 'long'
 *								
 *	
 *	'smarty_action'				name of smarty variable - see below
 *	'smarty_pager'				name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	 
 *	opt['smarty_action']			(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'was_deleted' - when message was successfully deleted
 *	
 *	opt['smarty_pager']			(pager)
 *	 associative array containing size of result and which page is returned
 *	
 */

class apu_msilo extends apu_base_class{
	var $smarty_action='default';
	var $im = array();
	var $pager = array();

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_ims', 'del_im');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_msilo(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['im_sending_script'] = "send_im.php";

		/* message on attributes update */
		$this->opt['msg_delete']['short'] =	&$lang_str['msg_im_deleted_s'];
		$this->opt['msg_delete']['long']  =	&$lang_str['msg_im_deleted_l'];
		
		/*** names of variables assigned to smarty ***/
		/* smarty action */
		$this->opt['smarty_action'] =		'action';

		$this->opt['smarty_im'] =			'im';

		/* pager */
		$this->opt['smarty_pager'] =		'pager';
		
	}

	function action_default(&$errors){
		global $data, $sess, $sess_ms_act_row;
		$this->controler->set_timezone();

		$data->set_act_row($sess_ms_act_row);

		if(false === $this->im = $data->get_IMs($this->user_id, $errors)) return false;

		$this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
		$this->pager['pos']=$data->get_act_row();
		$this->pager['items']=$data->get_num_rows();
		$this->pager['limit']=$data->get_showed_rows();
		$this->pager['from']=$data->get_res_from();
		$this->pager['to']=$data->get_res_to();


		foreach($this->im as $k=>$v){
			$this->im[$k]['url_reply']= $sess->url($this->opt['im_sending_script']."?kvrk=".uniqid("")."&im_sip_addr=".rawURLEncode($v['raw_src_addr']));
			$this->im[$k]['url_dele']= $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&im_dele_id=".rawURLEncode($v['mid']));
		}

		return true;
	}

	function action_dele(&$errors){
		global $data;
		if (!$data->del_IM($this->user_id, $_GET['im_dele_id'], $errors)) return false;

		return array("m_im_deleted=".RawURLEncode($this->opt['instance_id']));
	}
	
	/* this metod is called always at begining */
	function init(){
		global $sess, $sess_ms_act_row;
		parent::init();

		if (!$sess->is_registered('sess_ms_act_row')) $sess->register('sess_ms_act_row');
		if (!isset($sess_ms_act_row)) $sess_ms_act_row=0;
		
		if (isset($_GET['act_row'])) $sess_ms_act_row=$_GET['act_row'];

	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if (isset($_GET['im_dele_id'])){
			$this->action=array('action'=>"dele",
			                    'validate_form'=>false,
								'reload'=>true);
		}
		else $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_im_deleted']) and $_GET['m_im_deleted'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_delete'];
			$this->smarty_action="was_deleted";
		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_im'], $this->im);
		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
	}
	
}


?>

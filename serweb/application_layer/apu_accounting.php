<?
/*
 * $Id: apu_accounting.php,v 1.1 2004/08/25 10:19:48 kozlik Exp $
 */ 

/* Application unit accounting */

/*
   This application unit is used for view incoming and outgoing calls of user
   
   Configuration:
   --------------
   'attributes'					with which attributes should object work - default with all atributes
   'msg_update'					message which should be showed on attributes update - assoc array with keys 'short' and 'long'
   								default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
   'smarty_result'				name of smarty variable - see below
   'smarty_pager'				name of smarty variable - see below
   
   Exported smarty variables:
   --------------------------
   opt['smarty_result'] 		(acc_res)	array containing accounting informations
   opt['smarty_pager'] 			(pager)		associative array containing size of result and which page is returned
*/
 
class apu_accounting extends apu_base_class{
	var $acc_res;
	var $pager=array();

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('set_timezone', 'get_acc_entries');
	}

	/* return array of strings - required javascript files */
	function get_required_javascript(){
		return array("click_to_dial.js.php");
	}
	
	function apu_accounting(){
		/*** names of variables assigned to smarty ***/
		/* array containing accounting table */
		$this->opt['smarty_result'] =	'acc_res';
		/* pager */
		$this->opt['smarty_pager'] =	'pager';
	}

	/* this metod is called always at begining */
	function init(){
		global $sess, $sess_acc_act_row, $_GET;
		
		if (!$sess->is_registered('sess_acc_act_row')) $sess->register('sess_acc_act_row');
		if (!isset($sess_acc_act_row)) $sess_acc_act_row=0;
		
		if (isset($_GET['act_row'])) $sess_acc_act_row=$_GET['act_row'];
						 
		$this->acc_res=array();
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
	}
	
	/* realize action */
	function execute(&$errors){
		global $data, $sess_acc_act_row;
		
		do{
			$data->set_timezone($this->user_id, $errors);
			$data->set_act_row($sess_acc_act_row);
			if (false === $this->acc_res = $data->get_acc_entries($this->user_id, $errors)) break;

			$this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
			$this->pager['pos']=$data->get_act_row();
			$this->pager['items']=$data->get_num_rows();
			$this->pager['limit']=$data->get_showed_rows();
			$this->pager['from']=$data->get_res_from();
			$this->pager['to']=$data->get_res_to();
		}while (false);
	}
	
	/* add messages to given array */
	function return_messages(&$msgs){
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty, $_SERVER, $data;

		if(!$this->acc_res) $this->acc_res = array();
		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
		$smarty->assign_by_ref($this->opt['smarty_result'], $this->acc_res);
	}
}

?>
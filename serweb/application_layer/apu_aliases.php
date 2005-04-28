<?php
/**
 * Application unit aliases
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_aliases.php,v 1.2 2005/04/28 14:22:37 kozlik Exp $
 * @package   serweb
 */ 

/** Application unit aliases
 *
 *
 *	This application unit is used for manipulation with aliases
 *	notice: manipulation still not dome. only get list of aliases may be used
 *	   
 *	Configuration:
 *	--------------
 *	'allow_edit'				(bool) default: false
 *   set true if instance of this APU should be used for change values
 *	 by default only get list of aliases is enabled
 *
 *	'msg_add'					default: $lang_str['msg_alias_added_s'] and $lang_str['msg_alias_added_l']
 *	 message which should be showed on add new alias - assoc array with keys 'short' and 'long'
 *	
 *	'msg_update'					default: $lang_str['msg_alias_updated_s'] and $lang_str['msg_alias_updated_l']
 *	 message which should be showed on alias update - assoc array with keys 'short' and 'long'
 *	
 *	'msg_delete'					default: $lang_str['msg_alias_deleted_s'] and $lang_str['msg_alias_deleted_l']
 *	 message which should be showed on alias delete - assoc array with keys 'short' and 'long'
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
 *	'smarty_aliases'			name of smarty variable - see below
 *	
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_aliases'] 		(aliases)	
 *	 associative array containing user's aliases 
 *	 The array have same keys as function get_aliases (from data layer) returned. 
 *	
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_action']			(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'was_updated' - when user submited form and data was succefully stored
 *	  'was_added'   - when user submited form and new alias was succefully stored
 *	  'was_deleted' - when user delete alias
 *	  'edit'        - when user is editing alias
 *	
 */

class apu_aliases extends apu_base_class{
	var $smarty_action='default';
	var $aliases = array();
	var $act_alias;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_aliases', 'delete_alias', 'is_user_exists', 'add_new_alias');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_aliases(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['allow_edit'] =	false;

		/* message on alias update */
		$this->opt['msg_add']['short'] =	&$lang_str['msg_alias_added_s'];
		$this->opt['msg_add']['long']  =	&$lang_str['msg_alias_added_l'];

		$this->opt['msg_update']['short'] =	&$lang_str['msg_alias_updated_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_alias_updated_l'];

		$this->opt['msg_delete']['short'] =	&$lang_str['msg_alias_deleted_s'];
		$this->opt['msg_delete']['long']  =	&$lang_str['msg_alias_deleted_l'];

		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =		'form';
		/* smarty action */
		$this->opt['smarty_action'] =	'action';

		$this->opt['smarty_aliases'] =	'aliases';

		
		/* name of html form */
		$this->opt['form_name'] =			'';
	}

	function get_aliases(&$errors){
		global $data, $sess;

		if (false === $aliases = $data->get_aliases($this->user_id, $errors)) return false;
		
		foreach($aliases as $k=>$v){
			if ($this->action['action']=='edit' and 
			    $v['username']==$this->act_alias['username'] and
				$v['domain']  ==$this->act_alias['domain']){

				unset($this->aliases[$k]);
				continue;
			}

			$this->aliases[$k]['username'] = $v->username;
			$this->aliases[$k]['domain']   = $v->domain;
			$this->aliases[$k]['url_dele'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&al_dele_un=".RawURLEncode($v->username)."&al_dele_dom=".RawURLEncode($v->domain));
			$this->aliases[$k]['url_edit'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&al_edit_un=".RawURLEncode($v->username)."&al_edit_dom=".RawURLEncode($v->domain));
		}
		
		return true;
	}

	function action_add(&$errors){
		global $data;
		
		if (false === $data->add_new_alias($this->user_id, $_POST['al_username'], $_POST['al_domain'], $errors)) return false;

		return array("m_al_added=".RawURLEncode($this->opt['instance_id']));
	}

	function action_update(&$errors){
		global $data;
		
		if (false === $data->delete_alias($this->user_id, $this->act_alias['username'], $this->act_alias['domain'], $errors)) return false;
		if (false === $data->add_new_alias($this->user_id, $_POST['al_username'], $_POST['al_domain'], $errors)) return false;

		return array("m_al_updated=".RawURLEncode($this->opt['instance_id']));
	}

	function action_delete(&$errors){
		global $data;
		if (false === $data->delete_alias($this->user_id, $this->act_alias['username'], $this->act_alias['domain'], $errors)) return false;
		return array("m_al_deleted=".RawURLEncode($this->opt['instance_id']));
	}

	function action_edit(&$errors){
		if (false === $this->get_aliases($errors)) return false;
		$this->smarty_action="edit";
	}

	function action_default(&$errors){
		if (false === $this->get_aliases($errors)) return false;
		return true;
	}
	
	/* this metod is called always at begining */
	function init(){
		parent::init();
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){

		if ($this->opt['allow_edit']){	
			if ($this->was_form_submited()){	// Is there data to process?
				if ($_POST['al_id_u']){
					$this->act_alias['username'] = $_POST['al_id_u'];
					$this->act_alias['domain'] = $_POST['al_id_d'];
	
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
			
			if (isset($_GET['al_edit_un'])){
				$this->act_alias['username'] = $_GET['al_edit_un'];
				$this->act_alias['domain'] =   $_GET['al_edit_dom'];
	
				$this->action = array('action'=>"edit",
				                      'validate_form'=>false,
				                      'reload'=>false);
				return;
			}
	
			if (isset($_GET['al_dele_un'])){
				$this->act_alias['username'] = $_GET['al_dele_un'];
				$this->act_alias['domain'] =   $_GET['al_dele_dom'];
	
				$this->action = array('action'=>"delete",
				                      'validate_form'=>false,
				                      'reload'=>true);
				return;
			}
		}
		
		$this->action=array('action'=>"default",
		                    'validate_form'=>false,
		                    'reload'=>false);

	}
	
	/* create html form */
	function create_html_form(&$errors){
		parent::create_html_form($errors);

		if ($this->opt['allow_edit']){	
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"al_username",
										 "size"=>16,
										 "maxlength"=>64,
			                             "value"=>isset($this->act_alias['username']) ? $this->act_alias['username'] : ""));
	
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"al_domain",
										 "size"=>16,
										 "maxlength"=>128,
			                             "value"=>isset($this->act_alias['domain']) ? $this->act_alias['domain'] : $this->user_id->domain));
		
			$this->f->add_element(array("type"=>   "hidden",
			                             "name"=>  "al_id_u",
			                             "value"=> $this->act_alias['username']));
		
			$this->f->add_element(array("type"=>   "hidden",
			                             "name"=>  "al_id_d",
			                             "value"=> $this->act_alias['domain']));
		}
	}

	/* validate html form */
	function validate_form(&$errors){
		global $lang_str, $data;
		
		if (false === parent::validate_form($errors)) return false;

		if (!isset($_POST['al_domain']) or !$_POST['al_domain'])
			$_POST['al_domain'] = $this->user_id->domain;

		//check if alias exists
		if (0 > $alias_exists = $data->is_user_exists($_POST['al_username'], $_POST['al_domain'], $errors)) return false;
		if ($alias_exists){ 
			$errors[]=$lang_str['err_alias_already_exists_1']." ".$_POST['al_username']."@".$_POST['al_domain']." ".$lang_str['err_alias_already_exists_2']; 
			return false; 
		}

		return true;
	}

	/* callback function when some html form is invalid */
	function form_invalid(){
		$this->get_aliases($errors);
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;

		if (isset($_GET['m_al_updated']) and $_GET['m_al_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}

		if (isset($_GET['m_al_added']) and $_GET['m_al_added'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_add'];
			$this->smarty_action="was_added";
		}

		if (isset($_GET['m_al_deleted']) and $_GET['m_al_deleted'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_delete'];
			$this->smarty_action="was_deleted";
		}

	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;

		$smarty->assign_by_ref($this->opt['smarty_aliases'], $this->aliases);
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => "",
					 'before'      => "");
	}
}

?>

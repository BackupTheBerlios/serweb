<?php
/**
 * Application unit usrloc
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_usrloc.php,v 1.1 2005/04/21 15:09:45 kozlik Exp $
 * @package   serweb
 */ 

/* Application unit usrloc 
 *
 *
 *	This application unit is used for wiev usrloc and add new record to usrloc
 *	   
 *	Configuration:
 *	--------------
 *	
 *	'msg_delete'				default: $lang_str['msg_loc_contact_deleted_s'] and $lang_str['msg_loc_contact_deleted_l']
 *	 message which should be showed on contact delete - assoc array with keys 'short' and 'long'
 *								
 *	'msg_add'					default: $lang_str['msg_loc_contact_added_s'] and $lang_str['msg_loc_contact_added_l']
 *	 message which should be showed on contact add - assoc array with keys 'short' and 'long'
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
 *	  'was_added' - when user submited form and data was succefully stored
 *	  'was_deleted'  - when user delete contact
 *	
 */

define("UL_FOREVER",567648000);	//number of second for forever (18 years)

class apu_usrloc extends apu_base_class{
	var $smarty_action='default';
	var $usrloc;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('del_contact', 'get_usrloc', 'add_contact');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array('sip_address_completion.js.php', 'click_to_dial.js.php');
	}
	
	/* constructor */
	function apu_usrloc(){
		global $lang_str, $sess_lang;
		parent::apu_base_class();

		/* set default values to $this->opt */		

		/* message on contact delete */
		$this->opt['msg_delete']['short'] =	&$lang_str['msg_loc_contact_deleted_s'];
		$this->opt['msg_delete']['long']  =	&$lang_str['msg_loc_contact_deleted_l'];

		/* message on contact add */
		$this->opt['msg_add']['short'] =	&$lang_str['msg_loc_contact_added_s'];
		$this->opt['msg_add']['long']  =	&$lang_str['msg_loc_contact_added_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';
		
		$this->opt['form_submit']=array('type' => 'image',
										'text' => $lang_str['b_add'],
										'src'  => get_path_to_buttons("btn_add.gif", $sess_lang));
		
	}

	function get_usrloc(&$errors){
		global $data, $sess;
		
		if (false === $this->usrloc = $data->get_usrloc($this->user_id->uname, $this->user_id->domain, $errors)) return false;

		foreach($this->usrloc as $key=>$val){
			$this->usrloc[$key]['url_dele'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&ul_dele_id=".rawURLEncode($val['uri']));
//			$this->usrloc[$key]['url_edit'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&ul_edit_id=".rawURLEncode($val['uri']));
		}
		
		return true;
	}

	function action_default(&$errors){
		$this->controler->set_timezone();
		if (false === $this->get_usrloc($errors)) return false;
	}

	function action_delete(&$errors){
		if (false === $status = $data->del_contact($this->user_id->uname, $this->user_id->domain, $_GET['ul_dele_id'], $errors)) return false;

		return array("m_ul_deleted=".RawURLEncode($this->opt['instance_id']));
	}

	function action_add(&$errors){
		global $data;
		if (false === $status = $data->add_contact($this->user_id->uname, $this->user_id->domain, $_POST['ul_sip_address'], $_POST['ul_expires'], $errors)) return false;

		return array("m_ul_added=".RawURLEncode($this->opt['instance_id']));
	}
	
	/* this metod is called always at begining */
	function init(){
		parent::init();
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			$this->action=array('action'=>"add",
			                    'validate_form'=>true,
								'reload'=>true);
		}
		else if (isset($_GET['ul_dele_id'])){
			$this->action=array('action'=>"delete",
			                    'validate_form'=>false,
								'reload'=>true);
		}
		else $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
	}
	
	/* create html form */
	function create_html_form(&$errors){
		global $lang_str;
		parent::create_html_form($errors);

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"ul_sip_address",
									 "size"=>16,
									 "maxlength"=>128,
									 "minlength"=>1,
		                             "length_e"=>$lang_str['fe_not_filled_sip'],
		                             "valid_regex"=>"^".$this->controler->reg->sip_address."$",
		                             "valid_e"=>$lang_str['fe_not_valid_sip'],
									 "extrahtml"=>"onBlur='sip_address_completion(this)'"));

		$options = array(array("label"=>$lang_str['contact_expire_hour'],"value"=>3600),
						array("label"=>$lang_str['contact_expire_day'],"value"=>86400),
						array("label"=>$lang_str['contact_will_not_expire'],"value"=>UL_FOREVER));

		$this->f->add_element(array("type"=>"select",
									"name"=>"ul_expires",
									"options"=>$options,
									"size"=>1,
									"value"=>3600));
	}

	/* validate html form */
	function validate_form(&$errors){
		global $config, $perm;
		if (false === parent::validate_form($errors)) return false;

		if (is_array($config->denny_reg) and !$perm->have_perm("admin")){
			foreach ($config->denny_reg as $val){
				if (Ereg($val->reg, $_POST['ul_sip_address'])) {
					$errors[]=$val->label; 
					return false;
				}
			}
		}

		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_ul_added']) and $_GET['m_ul_added'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_add'];
			$this->smarty_action="was_added";
		}
		if (isset($_GET['m_ul_deleted']) and $_GET['m_ul_deleted'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_delete'];
			$this->smarty_action="was_deleted";
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
					 'before'      => 'sip_address_completion(ul_sip_address);');
	}
}


?>

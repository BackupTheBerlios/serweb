<?
/*
 * $Id: apu_phonebook.php,v 1.4 2005/04/21 15:09:45 kozlik Exp $
 */ 

/* Application unit phonebook */

/*
   This application unit is used for view and edit phonebook of user
   
   Configuration:
   --------------
   'numerical_target_only'		(bool) default: false
     if is true, only phonenumbers are alowed in username part of targer uri. 
	 The 'username_in_target_only' should be set to true too.
	 
   'username_in_target_only'	(bool) default: false
     If is true, user enter only username part of target uri. Domain name is 
	 appended by the option 'domain_for_targets'
	 
   'domain_for_targets'			(string) default: domain of loged in user
     see description of option 'username_in_target_only'
   

   'blacklist'					default: null
	 if isset, the regex check is performed agains all entered URIs. If URI match, it is not allowed

   'blacklist_e'				default: $lang_str['fe_not_allowed_uri']
	 error message that is displayed if URI is blacklisted

	 
   'get_user_status'			(bool) default: false
     should output array contain status of user?

   'get_user_aliases'			(bool) default: false
     should output array contain aliases of user?


   'msg_add'					default: $lang_str['msg_pb_contact_added_s'] and $lang_str['msg_pb_contact_added_l']
     message which should be showed on add new contact - assoc array with keys 'short' and 'long'

   'msg_update'					default: $lang_str['msg_pb_contact_updated_s'] and $lang_str['msg_pb_contact_updated_l']
     message which should be showed on contact update - assoc array with keys 'short' and 'long'

   'msg_delete'					default: $lang_str['msg_pb_contact_deleted_s'] and $lang_str['msg_pb_contact_deleted_l']
     message which should be showed on contact delete - assoc array with keys 'short' and 'long'


   'require_acknowledge_of_delete' 	(bool) default: false
     set to true if should be displayed confirmation page on deleting contact
     This option need to be supported in templates. For acknowledge deletion
	 must be submited form of this APU by default submit element.  Submiting by
	 anything else submit element cause cancel deletion.

   								
   'form_name'					(string) default: ''
     name of html form
   
   'form_submit'				(assoc)
     assotiative array describe submit element of form. For details see description 
	 of method add_submit in class form_ext

   'smarty_form'				name of smarty variable - see below
   'smarty_action'				name of smarty variable - see below
   'smarty_pager'				name of smarty variable - see below
   'smarty_phonebook'			name of smarty variable - see below
   'smarty_contact'				name of smarty variable - see below
   
   Exported smarty variables:
   --------------------------
   opt['smarty_form'] 			(form)			
     phplib html form
	 
   opt['smarty_action']			(action)
	  tells what should smarty display. Values:
   	  'default' - 
	  'was_updated' - when user submited form and data was succefully stored
	  'was_added'   - when user submited form and new contact was succefully stored
	  'was_deleted' - when user delete contact from phonebook
	  'edit'        - when user is editing contact
	  'delete_ack'  - when delete acknowledment is required
	  
   opt['smarty_pager']			(pager)
     associative array containing size of result and which page is returned

   opt['smarty_phonebook'] 		(phonebook)	
     associative array containing user's phonebook 
	 The array have same keys as function get_phonebook_entries (from data layer) returned. 
	 If username_in_target_only is true, array extra contains key sip_uri_username

   opt['smarty_contact'] 		(contact)	
     associative array containing info about current contact 
	 The array have same keys as function get_phonebook_entry (from data layer) returned. 

*/

class apu_phonebook extends apu_base_class{
	var $smarty_action='default';
	var $pager = array();
	var $phonebook = array();		//array containing whole phonebook
	var $contact = array();			//assoc - current contact
	var $act_pb_id = "";			//id of current contact
	var $js_before = "";
	var $js_after = "";

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('del_phonebook_entry', 'get_phonebook_entry', 'get_phonebook_entries', 'update_phonebook_entry');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array('sip_address_completion.js.php', 'click_to_dial.js.php');
	}
	
	/* constructor */
	function apu_phonebook(){
		global $lang_str, $config, $controler;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['numerical_target_only'] =		false;
		$this->opt['username_in_target_only'] =		false;
		$this->opt['domain_for_targets'] = $controler->user_id->domain;


		/* blacklist */
		$this->opt['blacklist'] = null;
		$this->opt['blacklist_e'] = &$lang_str['fe_not_allowed_uri'];


		$this->opt['get_user_status'] = 	false;
		$this->opt['get_user_aliases'] = 	false;
		

		/* message on attributes update */
		$this->opt['msg_add']['short'] =	&$lang_str['msg_pb_contact_added_s'];
		$this->opt['msg_add']['long']  =	&$lang_str['msg_pb_contact_added_l'];

		$this->opt['msg_update']['short'] =	&$lang_str['msg_pb_contact_updated_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_pb_contact_updated_l'];

		$this->opt['msg_delete']['short'] =	&$lang_str['msg_pb_contact_deleted_s'];
		$this->opt['msg_delete']['long']  =	&$lang_str['msg_pb_contact_deleted_l'];


		$this->opt['require_acknowledge_of_delete'] = 	false;
		

		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* pager */
		$this->opt['smarty_pager'] =		'pager';

		$this->opt['smarty_phonebook'] =	'phonebook';

		$this->opt['smarty_contact'] = 		'contact';


		/* name of html form */
		$this->opt['form_name'] =			'';
		
	}

	function get_phonebook(&$errors){
		global $data, $sess_pb_act_row, $sess;
		
		$data->set_act_row($sess_pb_act_row);
		
		$opt = array('get_user_status' => $this->opt['get_user_status'],
		             'get_user_aliases' => $this->opt['get_user_aliases']);
					 
		if ($this->action['action'] == 'edit')
			$opt['pbid'] = $this->act_pb_id;
		
		if (false === $this->phonebook = $data->get_phonebook_entries($this->user_id, $opt, $errors)) return false;

		$this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
		$this->pager['pos']=$data->get_act_row();
		$this->pager['items']=$data->get_num_rows();
		$this->pager['limit']=$data->get_showed_rows();
		$this->pager['from']=$data->get_res_from();
		$this->pager['to']=$data->get_res_to();

		foreach($this->phonebook as $key=>$val){
			$this->phonebook[$key]['url_dele'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&pb_dele_id=".$val['id']);
			$this->phonebook[$key]['url_edit'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&pb_edit_id=".$val['id']);

			if ($this->opt['username_in_target_only']) {
				$this->phonebook[$key]['sip_uri_username'] = $this->reg->get_username($val['sip_uri']);
			}

		}
		
		return true;
	}

	function convert_sip_uri($sip_uri){
		/* if user enter only username, convert it to full sip address first */
		if ($this->opt['username_in_target_only'] and !empty($sip_uri)){

			/* if user enter phonenumbers, convert it strict phonenumber */
			if ($this->opt['numerical_target_only'] and !empty($sip_uri)){
				$sip_uri = $this->reg->convert_phonenumber_to_strict($sip_uri);
			}
		
			$sip_uri = "sip:".$sip_uri."@".$this->opt['domain_for_targets'];
		}

		return $sip_uri;	
	}
	
	function action_add(&$errors){
		global $data;
		
		if (!$data->update_phonebook_entry($this->user_id, null, $_REQUEST['fname'], 
									$_REQUEST['lname'], $this->convert_sip_uri($_REQUEST['sip_uri']), $errors)) return false;

		return array("m_pb_contact_added=".RawURLEncode($this->opt['instance_id']));
	}

	function action_update(&$errors){
		global $data;
		
		if (!$data->update_phonebook_entry($this->user_id, $this->act_pb_id, $_REQUEST['fname'], 
									$_REQUEST['lname'], $this->convert_sip_uri($_REQUEST['sip_uri']), $errors)) return false;

		return array("m_pb_contact_updated=".RawURLEncode($this->opt['instance_id']));
	}

	function action_delete(&$errors){
		global $data;

		if (!$data->del_phonebook_entry($this->user_id, $this->act_pb_id, $errors)) return false;
	
		return array("m_pb_contact_deleted=".RawURLEncode($this->opt['instance_id']));
	}
	
	function action_default(&$errors){
		if (false === $this->get_phonebook($errors)) return false;
	}

	function action_edit(&$errors){
		if (false === $this->get_phonebook($errors)) return false;
		$this->smarty_action="edit";
	}

	function action_delete_ack(&$errors){
		if (false === $this->get_phonebook($errors)) return false;
		$this->smarty_action="delete_ack";
	}
	
	
	/* this metod is called always at begining */
	function init(){
		global $sess, $sess_pb_act_row;
		parent::init();

		if (!$sess->is_registered('sess_pb_act_row')) $sess->register('sess_pb_act_row');
		if (!isset($sess_pb_act_row)) $sess_pb_act_row=0;
		
		if (isset($_GET['act_row'])) $sess_pb_act_row=$_GET['act_row'];

		$this->reg = new Creg;				// create regular expressions class
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		$action_delete = array('action'=>"delete",
				                    'validate_form'=>false,
									'reload'=>true);

		/* take contact from whitepages and add to phonebook */
		if (isset($_GET['add_from_wp'])){
			$this->action = array('action'=>"add",
		                    'validate_form'=>true,
							'reload'=>true);
			return;
		}
	
		if ($this->was_form_submited()){	// Is there data to process?
			if (isset($_REQUEST['pb_delete_ack'])){
				$this->act_pb_id = $_REQUEST['pb_delete_ack'];
				$this->action = $action_delete;
				return;
			}

			if ($_REQUEST['id']){
				$this->act_pb_id = $_REQUEST['id'];
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
		
		if (isset($_GET['pb_edit_id'])){
			$this->act_pb_id = $_GET['pb_edit_id'];
			$this->action = array('action'=>"edit",
			                    'validate_form'=>false,
								'reload'=>false);
			return;
		}

		if (isset($_GET['pb_dele_id'])){
			$this->act_pb_id = $_GET['pb_dele_id'];

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
		global $lang_str, $data;
		parent::create_html_form($errors);

		if ($this->action['action'] == 'edit' or $this->action['action'] == 'delete_ack'){
			if (false === $this->contact = $data->get_phonebook_entry($this->user_id, $this->act_pb_id, $errors)) return false;
		}
		
		if ($this->action['action'] == 'delete_ack'){
			$this->f->add_element(array("type"=>"hidden",
			                             "name"=>"pb_delete_ack",
			                             "value"=>$this->act_pb_id));
		}
		else {
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"fname",
										 "size"=>16,
										 "maxlength"=>32,
			                             "value"=>isset($this->contact['fname'])?$this->contact['fname']:""));
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"lname",
										 "size"=>16,
										 "maxlength"=>32,
			                             "value"=>isset($this->contact['lname'])?$this->contact['lname']:""));

			$element_sip_uri = array("type"=>"text",
			                             "name"=>"sip_uri",
										 "size"=>16,
										 "maxlength"=>128);

			$sip_uri_val = isset($this->contact['sip_uri'])?$this->contact['sip_uri']:"";
			if ($this->opt['username_in_target_only']){
				/* parse username from request uri */
				$element_sip_uri["value"]       = $this->reg->get_username($sip_uri_val);
				
				/* if user should enter only phonenumber as username part of uri, add validating regex */
				if ($this->opt['numerical_target_only']){
					$element_sip_uri["valid_regex"] = "^(".$this->reg->phonenumber.")?$";
					$element_sip_uri["valid_e"]     = $lang_str['fe_not_valid_phonenumber'];
				}
			}
			else{
				$element_sip_uri["value"]       = $sip_uri_val;
				$element_sip_uri["valid_regex"] = "^(".$this->reg->sip_address.")?$";
				$element_sip_uri["valid_e"]     = $lang_str['fe_not_valid_sip'];
				$element_sip_uri["extrahtml"]   = "onBlur='sip_address_completion(this)'";
	
				$this->js_before = 'sip_address_completion(f.sip_uri);';
			}

			
			$this->f->add_element($element_sip_uri);
			
			$this->f->add_element(array("type"=>"hidden",
			                             "name"=>"id",
			                             "value"=>$this->act_pb_id));


			if ($this->opt['blacklist']){ //perform regex check against entered URIs
				$js_tmp = "if (window.RegExp) {\n".
						  " 	var blacklistreg = /".str_replace('/','\/',$this->opt['blacklist'])."/gi\n\n";
				/* if we are using phonenumbers, convert it to strict form */
				if ($this->opt['username_in_target_only'] and $this->opt['numerical_target_only'])
					$js_tmp .= "	".$this->reg->convert_phonenumber_to_strict_js("f.elements['sip_uri'].value", "blklist_tmp_uri").";\n";
				else 
					$js_tmp .= "	blklist_tmp_uri = f.elements['sip_uri'].value;\n";
					
					
				$js_tmp .= "	if (blacklistreg.test(blklist_tmp_uri)) {\n".
							"		alert('".addslashes($this->opt['blacklist_e'])."');\n".
							"		f.elements['sip_uri'].focus();\n".
							"		return(false);\n".
							"	}\n}\n";
										
				$this->js_after .= $js_tmp;
			}

		}


	}

	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)){
			$this->smarty_action="edit"; //if there was errors in submited form, set smarty action to edit
			return false;
		}

		if ($this->opt['blacklist']){ //perform regex check against entered URIs
			$sip_uri = $_POST['sip_uri'];

			/* if we are using phonenumbers, convert it to strict form */
			if ($this->opt['username_in_target_only'] and $this->opt['numerical_target_only']){
				$sip_uri = $this->reg->convert_phonenumber_to_strict($sip_uri);
			}
		
			/* check against blacklist */
			if (ereg($this->opt['blacklist'], $sip_uri)){
				$errors[] = $this->opt['blacklist_e'];
				$this->smarty_action="edit"; //if there was errors in submited form, set smarty action to edit
				return false;
			}
		}

		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_pb_contact_updated']) and $_GET['m_pb_contact_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}

		if (isset($_GET['m_pb_contact_added']) and $_GET['m_pb_contact_added'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_add'];
			$this->smarty_action="was_added";
		}

		if (isset($_GET['m_pb_contact_deleted']) and $_GET['m_pb_contact_deleted'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_delete'];
			$this->smarty_action="was_deleted";
		}

	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;

		if(!$this->phonebook) $this->phonebook = array();

		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
		$smarty->assign_by_ref($this->opt['smarty_phonebook'], $this->phonebook);
		$smarty->assign_by_ref($this->opt['smarty_contact'], $this->contact);
		
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => $this->js_after,
					 'before'      => $this->js_before);
	}
}
?>

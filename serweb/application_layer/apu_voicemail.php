<?
/*
 * $Id: apu_voicemail.php,v 1.4 2005/04/21 15:09:45 kozlik Exp $
 */ 

/* Application unit voicemail */

/*
   This application unit is used for upload and listen users greetings
   
   Configuration:
   --------------
   'use_radio_button'			If this is true, is to the form added radio button for select if voicemail should use
   								customized or standard greeting. Default: false.
   'max_file_size'				maximum size of uploaded file. Default: 1048576 (1 MB)
   'msg_upload'					message which should be showed on attributes update - assoc array with keys 'short' and 'long'
   								default: $lang_str['msg_greeting_stored_s'] and $lang_str['msg_greeting_stored_l']
   'msg_delete'					message which should be showed on greeting delete - assoc array with keys 'short' and 'long'
   								default: $lang_str['msg_greeting_deleted_s'] and $lang_str['msg_greeting_deleted_l']
   'smarty_form'				name of smarty variable - see below
   'smarty_action'				name of smarty variable - see below
   'smarty_download_url'		name of smarty variable - see below
   'form_name'					name of html form

   'form_submit'				(assoc)
     assotiative array describe submit element of form. For details see description 
	 of method add_submit in class form_ext
   
   Exported smarty variables:
   --------------------------
   opt['smarty_form'] 			(form)			phplib html form
   
   opt['smarty_action']			(action)		tells what should smarty display. Values:
   												'default' - 
												'was_updated' - when user submited form and data was succefully stored

   opt['smarty_download_url'] 	(vm_download_url)	url for download greeting
												
	Form fields
	-----------
	vm_greeting								for upload greeting file
	which_greeting							radio button for select if should be used standard or customized greeting
											(values: standard, customized)
	_hidden_customized_greeting_exists		for internal use						
													
*/
 
class apu_voicemail extends apu_base_class{
	var $smarty_action='default';

	/* if is set to true, method action_upload do nothing - file is already uploaded */
	var $action_upload_do_nothing = false;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('store_greeting', 'is_greeting_existent', 'remove_greeting', 'play_greeting');
	}

	/* constructor */
	function apu_voicemail(){
		global $lang_str, $config, $sess_lang;
		parent::apu_base_class();

		$this->opt['use_radio_button'] =	false;

		$this->opt['max_file_size'] = 1048576; //1MB

		/* message on attributes update */
		$this->opt['msg_upload']['short'] =	&$lang_str['msg_greeting_stored_s'];
		$this->opt['msg_upload']['long']  =	&$lang_str['msg_greeting_stored_l'];

		$this->opt['msg_delete']['short'] = &$lang_str['msg_greeting_deleted_s'];
		$this->opt['msg_delete']['long']  = &$lang_str['msg_greeting_deleted_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		
		$this->opt['smarty_download_url'] = 'vm_download_url';

		/* name of html form */
		$this->opt['form_name'] =			'';

		$this->opt['form_submit']=array('type' => 'image',
										'text' => $lang_str['b_upload_greeting'],
										'src'  => get_path_to_buttons("btn_upload_greeting.gif", $sess_lang));
	}


	function action_download(&$errors){
		global $data;

		if (false === $data->play_greeting($this->user_id, $errors)) return false;

		return true;
	}

	
	function action_upload(&$errors){
		global $data;

		/* if file is already uploaded, do nothing */
		if ($this->action_upload_do_nothing) return array();
	
		/* otherwise save the file */
		if (false === $data->store_greeting($this->user_id, $_FILES['vm_greeting']['tmp_name'], $errors)) return false;

		return array("m_file_uploaded=".RawURLEncode($this->opt['instance_id']));
	}
	
	function action_delete(&$errors){
		global $data;
	
		if (false === $data->remove_greeting($this->user_id, $errors)) return false;

		return array("m_file_deleted=".RawURLEncode($this->opt['instance_id']));
	}
		
	/* this metod is called always at begining */
	function init(){
		global $_SERWEB;
		parent::init();
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		$action_upload = array('action'=>"upload",
		                       'validate_form'=>true,
							   'reload'=>true);

		$action_delete = array('action'=>"delete",
		                       'validate_form'=>true,
							   'reload'=>true);

		$action_default = array('action'=>"default",
			                    'validate_form'=>false,
		                        'reload'=>false);

		if (isset($_GET['vm_download_g']) and $_GET['vm_download_g'] == $this->opt['instance_id']){
			$this->action=array('action'=>"download",
			                    'validate_form'=>false,
								'reload'=>false,
								'alone'=>true);
								
			return;
		}


		if ($this->was_form_submited()){	// Is there data to process?
		
		/*
			Which action will be performed depending on which_greeting and greeting file form elemets
			
			  which_greeting   |   greeting file   |   action
			-----------------------------------------------------------
			  standard         |   given           |  upload
			  standard         |   not given       |  delete
			  customized       |   given           |  upload
			  customized       |   not given       |  (upload *)
			  
			  *) if greeting file exists, do nothing and if file isn't exist report error
		
		 */
		
			if ($this->opt['use_radio_button']){
				/* if standard greeting is selected and no greeting file is given */
				if ($_POST['which_greeting'] == 'standard' and $_FILES['vm_greeting']['error'] == UPLOAD_ERR_NO_FILE) {
					$this->action = &$action_delete;
					return;
				}

				$this->action = &$action_upload;
				return;
			}
			
			$this->action = &$action_upload;
			return;

		}
		else $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
	}
	
	/* validate html form */
	function validate_form(&$errors){
		global $lang_str, $data;
		
		if (false === parent::validate_form($errors)) return false;

		
		if ($this->opt['use_radio_button']){
			//if action is delete, nothing for validate
			if ($this->action['action'] == 'delete') return true;
			
			/* action upload */
			
			/* if greeting should be customized and action is not given */
			if ($_POST['which_greeting'] == 'customized' and $_FILES['vm_greeting']['error'] == UPLOAD_ERR_NO_FILE){
				/* if file exists, is it ok and we don't have to do nothing */
				if ($data->is_greeting_existent($this->user_id, $errors)){
					$this->action_upload_do_nothing = true;
					return true;
				}
				/* otherwise it is wrong, report error */
				else{
					$errors[]=$lang_str['fe_no_greeeting_file'];
					return false;
				}
			}
			
			/* in other cases check if file was successfuly uploaded */
			return $this->validate_form_check_greeting_file($errors);
		}

		return $this->validate_form_check_greeting_file($errors);

	}

	/* validate if given greeting file is right */
	function validate_form_check_greeting_file(&$errors){
		global  $lang_str;

		if ($_FILES['vm_greeting']['error'] == UPLOAD_ERR_FORM_SIZE or 
			$_FILES['vm_greeting']['error'] == UPLOAD_ERR_INI_SIZE){
			$errors[]=$lang_str['fe_greeting_file_too_big'];
			return false;
		}

		if (!is_uploaded_file($_FILES['vm_greeting']['tmp_name'])){
			$errors[]=$lang_str['fe_no_greeeting_file'];
			return false;
		}

		if (filesize($_FILES['vm_greeting']['tmp_name'])==0){
			$errors[]=$lang_str['fe_invalid_greeting_file'];
			return false;
		}

		if ($_FILES['vm_greeting']['type'] != "audio/wav"){
			$errors[]=$lang_str['fe_greeting_file_no_wav'];
			return false;
		}

		return true;
	}
	
	/* create html form */
	function create_html_form(&$errors){
		global $data, $config;
		parent::create_html_form($errors);

		$this->f->add_element(array("type"=>"file",
		                             "name"=>"vm_greeting",
									 "size"=>$this->opt['max_file_size'],
		                             "value"=>""));

		/* if should be used radio button */									 
		if ($this->opt['use_radio_button']){
			/* get state of the button */
			if ($data->is_greeting_existent($this->user_id, $errors))
				$which_greeting_value = 'customized';
			else
				$which_greeting_value = 'standard';
		
			/* add it to form */
			$this->f->add_element(array("type"=>"radio",
			                             "name"=>"which_greeting",
										 "options" => array(
										 				array('label' => 'standard greeting',
														      'value' => 'standard'),
										 				array('label' => 'customized greeting',
														      'value' => 'customized')
										 			  ),
			                             "value"=>$which_greeting_value));
		
			$this->f->add_element(array("type"=>"hidden",
			                             "name"=>"_hidden_customized_greeting_exists",
			                             "value"=>($which_greeting_value=='customized' ? '1' : '0')));
		}
	}

	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_file_uploaded']) and $_GET['m_file_uploaded'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_upload'];
			$this->smarty_action="was_uploaded";
		}

		else if (isset($_GET['m_file_deleted']) and $_GET['m_file_deleted'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_delete'];
			$this->smarty_action="was_deleted";
		}

	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty, $sess;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign($this->opt['smarty_download_url'], 
			$sess->url($_SERVER['PHP_SELF']."?vm_download_g=".RawURLEncode($this->opt['instance_id'])));
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		global $lang_str;

		if ($this->opt['use_radio_button']){
			$after = "
				//get value of radio
				var which_greeting;
				if (f.which_greeting[0].checked) which_greeting = f.which_greeting[0].value;
				else if (f.which_greeting[1].checked) which_greeting = f.which_greeting[1].value;

				// necessitate greeting file only if it is not uploaded yet and 
				// radio button is switchet to customized greeting
				
				if (f._hidden_customized_greeting_exists.value=='0' && which_greeting=='customized' && f.vm_greeting.value==''){
					alert('".addslashes($lang_str['fe_no_greeeting_file'])."');
					f.vm_greeting.focus();
					return (false);
				}
				
			";
		}
		else{
			$after = "
				if (f.vm_greeting.value==''){
					alert('".addslashes($lang_str['fe_no_greeeting_file'])."');
					f.vm_greeting.focus();
					return (false);
				}
			";
		}

		
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => $after);
	}

}

?>

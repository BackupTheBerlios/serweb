<?
/*
 * $Id: apu_whitelist.php,v 1.1 2004/09/17 17:34:11 kozlik Exp $
 */ 

/* Application unit whitelist */

/*
   This application unit is used for edit whitelist of user
   
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

   
   'msg_update'					default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
     message which should be showed on attributes update - assoc array with keys 'short' and 'long'
   								
   'form_name'					(string) default: ''
     name of html form
   
   'form_submit'				(assoc)
     assotiative array describe submit element of form. For details see description 
	 of method add_submit in class form_ext

   'smarty_form'				name of smarty variable - see below
   'smarty_action'				name of smarty variable - see below
   'smarty_js_add'				name of smarty variable - see below
   'smarty_js_edit'				name of smarty variable - see below
   'smarty_js_drop'				name of smarty variable - see below
   
   Exported smarty variables:
   --------------------------
   opt['smarty_form'] 			(form)			
     phplib html form
	 
   opt['smarty_action']			(action)
	  tells what should smarty display. Values:
   	  'default' - 
	  'was_updated' - when user submited form and data was succefully stored

   opt['smarty_js_add'] 		(js_url_add)			
     contain url for add new uri to whitelist
   
   opt['smarty_js_edit'] 		(js_url_edit)			
     contain url for change uri in whitelist

   opt['smarty_js_drop'] 		(js_url_drop)			
     contain url for drop uri from whitelist

*/

class apu_whitelist extends apu_base_class{
	var $smarty_action='default';
	var $js_before = '';
	var $reg;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_whitelist', 'add_whitelist_entry', 'del_whitelist_entry');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array('whitelist.js', 'sip_address_completion.js.php');
	}
	
	/* constructor */
	function apu_whitelist(){
		global $lang_str, $config, $controler;
		parent::apu_base_class();

		/* set default values to $this->opt */		

		$this->opt['numerical_target_only'] =		false;
		$this->opt['username_in_target_only'] =		false;
		$this->opt['domain_for_targets'] = $controler->user_id->domain;

		
		/* message on attributes update */
		$this->opt['msg_update']['short'] =	&$lang_str['msg_changes_saved_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_changes_saved_l'];

		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';

		$this->opt['smarty_js_add'] =		'js_url_add';
		$this->opt['smarty_js_edit'] =		'js_url_edit';
		$this->opt['smarty_js_drop'] =		'js_url_drop';

		
		/* name of html form */
		$this->opt['form_name'] =			'';
		
		$this->opt['form_submit']=array('type' => 'image',
										'text' => 'save',
										'src'  => $config->img_src_path."butons/b_save.gif");

	}

	function action_update(&$errors){
		global $data;
		
		if ($this->whitelist === false) return false; /* an error in getting whitelist */

		/* if $_POST['whitelist'] is missing, create it as empty array */
		if (!isset($_POST['whitelist']) or ! is_array($_POST['whitelist'])) $_POST['whitelist']=array();

		/* in which fields of $this->whitelist array are values with wich we are eorking?
		   It may by only username in fields 'username_uri' or whole sip address in field 'uri'
		 */
		$uri_field = $this->opt['username_in_target_only'] ? 'username_uri' :'uri';
		
		/* copy values from corresponding fields to one dimensional array. Preserve keys. */
		$current_whitelist=array();
		foreach($this->whitelist as $key => $val) $current_whitelist[$key] = $val[$uri_field];

		/* get uris which should be deleted - is missing in POST array. Key are still preserved */
		$del=array_diff($current_whitelist, $_POST['whitelist']);
		if (is_array($del))
			foreach($del as $key => $val){
				$opt = array('primary_key' => $this->whitelist[$key]['primary_key']);
				if (false === $data->del_whitelist_entry($this->user_id, $opt, $errors)) return false;
			}
		

		/* get uris which should be inserted - is missing in $this->whitelist array. Key aren't preserved */
		$ins=array_diff($_POST['whitelist'], $current_whitelist);

		if (is_array($ins))
			foreach($ins as $val){
				/* if we are working only with usernames, append the tomain and initial 'sip:' */
				if ($this->opt['username_in_target_only']) $val = "sip:".$val."@".$this->opt['domain_for_targets'];
			
				$values = array ('uri' => $val);
				if (false === $data->add_whitelist_entry($this->user_id, $values, NULL, $errors)) return false;
			}
		
		return array("m_wlist_updated=".RawURLEncode($this->opt['instance_id']));
	}
	
	/* this metod is called always at begining */
	function init(){
		parent::init();

		$this->reg = new Creg;				// create regular expressions class
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			$this->action=array('action'=>"update",
			                    'validate_form'=>true,
								'reload'=>true);
		}
		else $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
	}
	
	/* create html form */
	function create_html_form(&$errors){
		global $data, $lang_str;
		parent::create_html_form($errors);

		if (false === $this->whitelist = $data->get_whitelist($this->user_id, NULL, $errors)) return;

		$uri_field = 'uri';
		/* if we are using phonenumbers, convert all sip addresses to phonenumber */
		if ($this->opt['username_in_target_only']) {
			$uri_field = 'username_uri';
			foreach($this->whitelist as $key=>$val){
				$this->whitelist[$key]['username_uri'] = $this->reg->get_username($val['uri']);
			}
		}
		
		$opt=array();
		foreach($this->whitelist as $val){
			$opt[] = array('label' => $val[$uri_field],
		                   'value' => $val[$uri_field]);
		}

		
		$this->f->add_element(array("type"=>"select",
		                             "name"=>"whitelist",
									 "size"=>10,
									 "options"=>$opt,
									 "multiple"=>true));

		$element_edit_field = array("type"=>"text",
		                             "name"=>"editfield",
									 "size"=>16);									 

		if ($this->opt['username_in_target_only']) {
			if ($this->opt['numerical_target_only']){
				$element_edit_field["valid_regex"] = "^(".$this->reg->phonenumber.")?$";
				$element_edit_field["valid_e"]     = $lang_str['fe_not_valid_phonenumber'];
			}
		}
		else {
			$element_edit_field["valid_regex"] = "^(".$this->reg->sip_address.")?$";
			$element_edit_field["valid_e"]     = $lang_str['fe_not_valid_sip'];
			$element_edit_field["extrahtml"]   = "onBlur='sip_address_completion(this)'";
		}
											 
		$this->f->add_element($element_edit_field);
									 
		$this->f->add_element(array("type"=>"hidden",
		                             "name"=>"hidden_edit_field"));
									 
		/* all options in whitelist must be selected in order to browser send them */
		$this->js_before = "select_all_options(f.whitelist);";									 
	}

	function validate_select_entries(&$errors){
		global $lang_str;

		if (isset($_POST['whitelist']) and is_array($_POST['whitelist'])){
			/* check all entered values if they are correct */
			foreach ($_POST['whitelist'] as $key => $val){

				if ($this->opt['username_in_target_only']){
					if ($this->opt['numerical_target_only']){
						if (!ereg("^(".$this->reg->phonenumber.")?$", $val)){
							$erors[]=$lang_str['fe_not_valid_phonenumber'];
							return false;
						}
						/* if user enter phonenumbers, convert it to strict phonenumber */
						$_POST['whitelist'][$key] = $this->reg->convert_phonenumber_to_strict($val);
					}
				}
				else{
					if (!ereg("^(".$this->reg->sip_address.")?$", $val)){
						$errors[]=$lang_str['fe_not_valid_sip'];
						return false;
					}
				}
			}
		}
		return true;
	}
	
	function load_default_select_entries(){
		$opt=array();
		foreach((array)$_POST['whitelist'] as $val){
			$opt[] = array('label' => $val[$uri_field],
		                   'value' => $val[$uri_field]);
		}
	
		$this->f->elements['whitelist']['ob']->options = &$opt[];
	}
	
	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) {
			$this->load_default_select_entries();
			return false;
		}

		if (!$this->validate_select_entries($errors)){
			$this->load_default_select_entries();
			return false;
		}
		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_wlist_updated']) and $_GET['m_wlist_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);

		$form_fields = "document.".$this->opt['form_name'].".whitelist, ".
		               "document.".$this->opt['form_name'].".editfield, ".
		               "document.".$this->opt['form_name'].".hidden_edit_field";
					   
		$smarty->assign($this->opt['smarty_js_add'],  "javascript: wlist_add(".$form_fields.");");
		$smarty->assign($this->opt['smarty_js_edit'], "javascript: wlist_edit(".$form_fields.");");
		$smarty->assign($this->opt['smarty_js_drop'], "javascript: wlist_drop(".$form_fields.");");

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
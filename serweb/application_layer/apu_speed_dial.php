<?
/*
 * $Id: apu_speed_dial.php,v 1.4 2004/11/18 15:47:12 kozlik Exp $
 */ 

/* Application unit speed dial */

/*
   This application unit is used for view and change values in table speed dial
   
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
   
   'domain_for_requests'		(string) default: domain of loged in user
     domain which is used for request URIs (column 'domain_from_req_uri' in DB table)

   'msg_update'					default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
     message which should be showed on attributes update - assoc array with keys 'short' and 'long'

	'blacklist'					default: null
	 if isset, the regex check is performed agains all entered URIs. If URI match, it is not allowed

	'blacklist_e'				default: $lang_str['fe_not_allowed_uri']
	 error message that is displayed if URI is blacklisted
   								
   'form_name'					(string) default: ''
     name of html form
   
   'form_submit'				(assoc)
     assotiative array describe submit element of form. For details see description 
	 of method add_submit in class form_ext

   'smarty_form'				name of smarty variable - see below
   'smarty_speed_dials'			name of smarty variable - see below
   'smarty_pager'				name of smarty variable - see below
   'smarty_action'				name of smarty variable - see below
   
   Exported smarty variables:
   --------------------------
   opt['smarty_form'] 			(form)			
     phplib html form
	 
   opt['smarty_speed_dials']	(speed_dials)
     array containing info about speed dials
	 
   opt['smarty_pager']			(pager)
     associative array containing size of result and which page is returned
   
   opt['smarty_action']			(action)
	  tells what should smarty display. Values:
   	  'default' - 
	  'was_updated' - when user submited form and data was succefully stored

*/


class apu_speed_dial extends apu_base_class{
	var $smarty_action='default';
	var $pager = array();
	var $speed_dials = array();
	var $js_blacklist = array();

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_speed_dials', 'update_speed_dial');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array('sip_address_completion.js.php');
	}
	
	/* constructor */
	function apu_speed_dial(){
		global $lang_str, $config, $controler;
		parent::apu_base_class();

		/* set default values to $this->opt */		

		$this->opt['numerical_target_only'] =		false;
		$this->opt['username_in_target_only'] =		false;
		$this->opt['domain_for_targets'] = $controler->user_id->domain;

		$this->opt['domain_for_requests'] = $controler->user_id->domain;

		/* blacklist */
		$this->opt['blacklist'] = null;
		$this->opt['blacklist_e'] = &$lang_str['fe_not_allowed_uri'];
		
		/* message on attributes update */
		$this->opt['msg_update']['short'] =	&$lang_str['msg_changes_saved_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_changes_saved_l'];
		
		/*** names of variables assigned to smarty ***/
		/* pager */
		$this->opt['smarty_pager'] =		'pager';
		/* speed dials */
		$this->opt['smarty_speed_dials'] =	'speed_dials';
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';

		$this->opt['form_submit']=array('type' => 'image',
										'text' => 'save',
										'src'  => $config->img_src_path."butons/b_save.gif");

	}


	function action_default(&$errors){
		global $data, $sess_sd_act_row;
		return true;
	}

	function action_update(&$errors){
		global $data;
		
		/* walk throught all speed dials on this page and changed rows save to DB */
		foreach ($this->speed_dials as $key => $val){
			$new_uri = $_POST['new_uri_'.$val['index']];
		

			/* if user enter only username, convert it to full sip address first */
			if ($this->opt['username_in_target_only'] and !empty($new_uri)){

				/* if user enter phonenumbers, convert it strict phonenumber */
				if ($this->opt['numerical_target_only'] and !empty($new_uri)){
					$new_uri = $this->reg->convert_phonenumber_to_strict($new_uri);
				}
			
				$new_uri = "sip:".$new_uri."@".$this->opt['domain_for_targets'];
			}
			
			
			if ($val['new_request_uri'] != $new_uri or
			    $val['first_name'] != $_POST['fname_'.$val['index']] or
			    $val['last_name']  != $_POST['lname_'.$val['index']]){
			
				$opt = array( 'insert' => $val['empty'],
				              'primary_key' => $val['primary_key']);

				$values = array('new_request_uri' => $new_uri,
				                'first_name' => $_POST['fname_'.$val['index']],
								'last_name'  => $_POST['lname_'.$val['index']]);			
								
				if (false === $data->update_speed_dial($this->user_id, $values, $opt, $errors)) return false;
			}
		
		}
	
	
		return array("m_sd_updated=".RawURLEncode($this->opt['instance_id']));
	}
	
	/* this metod is called always at begining */
	function init(){
		global $sess, $sess_sd_act_row;
		parent::init();
		
		if (!$sess->is_registered('sess_sd_act_row')) $sess->register('sess_sd_act_row');
		if (!isset($sess_sd_act_row)) $sess_sd_act_row=0;
		
		if (isset($_GET['act_row'])) $sess_sd_act_row=$_GET['act_row'];

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
		global $data, $lang_str, $sess_sd_act_row;
		parent::create_html_form($errors);

		$opt=array('solid_interval_of_usernames' => true,
		           'from' => $sess_sd_act_row,
				   'to' => $sess_sd_act_row+9);
		
		if (false === $this->speed_dials = $data->get_speed_dials($this->user_id, $opt, $errors)) return false;

		// configure pager - in this case $data->get_speed_dials doesn't depend on methods set* bellow
		$data->set_act_row($sess_sd_act_row);
		$data->set_num_rows(100);
		$data->set_showed_rows(10);
		$this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
		$this->pager['pos']=$data->get_act_row();
		$this->pager['items']=$data->get_num_rows();
		$this->pager['limit']=$data->get_showed_rows();
		$this->pager['from']=$data->get_res_from();
		$this->pager['to']=$data->get_res_to();

		
		/* fill in 'domain_from_req_uri' for entries which aren't in DB */
		foreach ($this->speed_dials as $key => $val){
			if ($val['empty']) $this->speed_dials[$key]['domain_from_req_uri'] = $this->opt['domain_for_requests'];
		}
		
		$i=0;
		foreach ($this->speed_dials as $key => $val){
			$index = sprintf("%02u", $i);
			/* add index into speed_dial array */
			$this->speed_dials[$key]['index'] = $index;
			
			$element_new_uri = array("type"=>"text",
			                             "name"=>"new_uri_".$index,
										 "size"=>16,
										 "maxlength"=>128,
			                             "value"=>$val['new_request_uri']);
										 
			if ($this->opt['username_in_target_only']){
				/* parse username from request uri */
				$element_new_uri["value"]       = $this->reg->get_username($val['new_request_uri']);
				
				/* if user should enter only phonenumber as username part of uri, add validating regex */
				if ($this->opt['numerical_target_only']){
					$element_new_uri["valid_regex"] = "^(".$this->reg->phonenumber.")?$";
					$element_new_uri["valid_e"]     = $lang_str['fe_not_valid_phonenumber'];
				}
			}
			else{
				$element_new_uri["value"]       = $val['new_request_uri'];
				$element_new_uri["valid_regex"] = "^(".$this->reg->sip_address.")?$";
				$element_new_uri["valid_e"]     = $lang_str['fe_not_valid_sip'];
				$element_new_uri["extrahtml"]   = "onBlur='sip_address_completion(this)'";
			}
			
			$this->f->add_element($element_new_uri);
										 
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"fname_".$index,
										 "size"=>16,
										 "maxlength"=>128,
			                             "value"=>$val['first_name']));
										 
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"lname_".$index,
										 "size"=>16,
										 "maxlength"=>128,
			                             "value"=>$val['last_name']));

			if ($this->opt['blacklist']){ //perform regex check against entered URIs
				$js_tmp = "if (window.RegExp) {\n".
						  " 	var blacklistreg = /".str_replace('/','\/',$this->opt['blacklist'])."/gi\n\n";
				/* if we are using phonenumbers, convert it to strict form */
				if ($this->opt['username_in_target_only'] and $this->opt['numerical_target_only'])
					$js_tmp .= "	".$this->reg->convert_phonenumber_to_strict_js("f.elements['new_uri_".$index."'].value", "blklist_tmp_uri").";\n";
				else 
					$js_tmp .= "	blklist_tmp_uri = f.elements['new_uri_".$index."'].value;\n";
					
					
				$js_tmp .= "	if (blacklistreg.test(blklist_tmp_uri)) {\n".
							"		alert('".addslashes($this->opt['blacklist_e'])."');\n".
							"		f.elements['new_uri_".$index."'].focus();\n".
							"		return(false);\n".
							"	}\n}\n";
										
				$this->js_blacklist[] = $js_tmp;
			}
										 
			$i++;
		}

	}

	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;
		
		if ($this->opt['blacklist']){ //perform regex check against entered URIs

			foreach ($this->speed_dials as $key => $val){
				$new_uri = $_POST['new_uri_'.$val['index']];

				/* skip empty fields */
				if (!empty($new_uri)){

					/* if we are using phonenumbers, convert it to strict form */
					if ($this->opt['username_in_target_only'] and $this->opt['numerical_target_only']){
						$new_uri = $this->reg->convert_phonenumber_to_strict($new_uri);
					}
				
					/* check against blacklist */
					if (ereg($this->opt['blacklist'], $new_uri)){
						$errors[] = $this->opt['blacklist_e'];
						return false;
					}
				}
			}
		}
		
		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_sd_updated']) and $_GET['m_sd_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
		$smarty->assign_by_ref($this->opt['smarty_speed_dials'], $this->speed_dials);
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){

		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => implode($this->js_blacklist, ""),
					 'before'      => '');
	}
	
}

?>

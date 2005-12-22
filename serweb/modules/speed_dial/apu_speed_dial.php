<?
/*
 * $Id: apu_speed_dial.php,v 1.3 2005/12/22 13:28:09 kozlik Exp $
 */ 

/** Application unit speed dial */

/**
 *	This application unit is used for view and change values in table speed dial
 *	   
 *	Configuration:
 *	--------------
 *	'numerical_target_only'		(bool) default: false
 *		if is true, only phonenumbers are alowed in username part of targer uri. 
 *		The 'username_in_target_only' should be set to true too.
 *	 
 *	'username_in_target_only'	(bool) default: false
 *		If is true, user enter only username part of target uri. Domain name is 
 *		appended by the option 'domain_for_targets'
 *	 
 *	'domain_for_targets'			(string) default: domain of loged in user
 *		see description of option 'username_in_target_only'
 *	
 *	'domain_for_requests'		(string) default: domain id of loged in user
 *		domain id which is used for request URIs (column 'dial_did' in DB table)
 *	
 *	'msg_update'					default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
 *		message which should be showed on attributes update - assoc array with keys 'short' and 'long'
 *	
 *	'blacklist'					default: null
 *		if isset, the regex check is performed agains all entered URIs. If URI match, it is not allowed
 *	
 *	'blacklist_e'				default: $lang_str['fe_not_allowed_uri']
 *		error message that is displayed if URI is blacklisted
 *	   								
 *	'form_name'					(string) default: ''
 *		name of html form
 *	
 *	'form_submit'				(assoc)
 *		assotiative array describe submit element of form. For details see description 
 *		of method add_submit in class form_ext
 *	
 *	'fname_max_chars'			(int) default: 128
 *		maximum number of characters in fields fname
 *	
 *	'lname_max_chars'			(int) default: 128
 *		maximum number of characters in fields lname
 *	
 *	'new_uri_max_chars'			(int) default: 128
 *		maximum number of characters in fields new_uri
 *	
 *		 
 *	'sort_asc_on_change_col'		(bool) default: true;
 *		If true, sorting direction is set to ASCENDING when column by which is 
 *		output sorted is changed
 *		 
 *		 
 *	'validate_funct'				(string) default: null
 *		name of enhanced validate function of entered uri
 *		validate function must return true or false, first parametr is reference
 *		to given URI (function can change it)
 *		and second one is  is reference to errors array - function can add error 
 *		message to it which is dispayed to user
 *		 
 *		
 *		example:
 *			function my_validate_form(&$uri, &$errors){
 *				//validate only one attribute
 *				if (ereg('^[0-9]+$', $uri)) return true;
 *				else {
 *					$errors[]="not number";
 *					return false;
 *				}
 *			}
 *	
 *			set_opt('validate_funct') = 'my_validate_function';
 *	 
 *	 
 *	'smarty_form'				name of smarty variable - see below
 *	'smarty_speed_dials'			name of smarty variable - see below
 *	'smarty_pager'				name of smarty variable - see below
 *	'smarty_action'				name of smarty variable - see below
 *	'smarty_sort_from_uri'		name of smarty variable - see below
 *	'smarty_sort_to_uri'			name of smarty variable - see below
 *	'smarty_sort_fname'			name of smarty variable - see below
 *	'smarty_sort_lname'			name of smarty variable - see below
 *	'smarty_sort_by'				name of smarty variable - see below
 *	'smarty_sort_dir'			name of smarty variable - see below
 *	   
 *	   
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_form'] 			(form)			
 *		phplib html form
 *	
 *	opt['smarty_speed_dials']	(speed_dials)
 *		array containing info about speed dials
 *	
 *	opt['smarty_pager']			(pager)
 *		associative array containing size of result and which page is returned
 *	
 *	opt['smarty_action']			(action)
 *		tells what should smarty display. Values:
 *		'default' - 
 *		'was_updated' - when user submited form and data was succefully stored
 *	
 *	  
 *	opt['smarty_sort_from_uri'] 	(url_sort_from_uri)
 *	opt['smarty_sort_to_uri']   	(url_sort_to_uri)
 *	opt['smarty_sort_fname']   		(url_sort_fname)
 *	opt['smarty_sort_lname']   		(url_sort_lname)
 *		contain URL for change sorting
 *	
 *	opt['smarty_sort_by']   		(sort_by)
 *		Contain name of collumn by which the output is sorted. Contain one of:
 *			"from_uri", "to_uri", "fname", "lname"
 *		
 *	opt['smarty_sort_desc']   		(sort_desc)
 *		True if sorting direction is descending. False otherwise.
 *	
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

		$this->opt['domain_for_requests'] = $controler->user_id->get_did();

		/* blacklist */
		$this->opt['blacklist'] = null;
		$this->opt['blacklist_e'] = &$lang_str['fe_not_allowed_uri'];
		
		/* message on attributes update */
		$this->opt['msg_update']['short'] =	&$lang_str['msg_changes_saved_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_changes_saved_l'];

		$this->opt['sort_asc_on_change_col'] = true;	

		$this->opt['validate_funct'] = null;	
		
		/*** names of variables assigned to smarty ***/
		/* pager */
		$this->opt['smarty_pager'] =		'pager';
		/* speed dials */
		$this->opt['smarty_speed_dials'] =	'speed_dials';
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';

		/* URLs for change sorting */
		$this->opt['smarty_sort_from_uri'] =	'url_sort_from_uri';
		$this->opt['smarty_sort_to_uri'] = 		'url_sort_to_uri';
		$this->opt['smarty_sort_fname'] = 		'url_sort_fname';
		$this->opt['smarty_sort_lname'] = 		'url_sort_lname';

		/* Sorting direction */
		$this->opt['smarty_sort_by'] = 			'sort_by';
		$this->opt['smarty_sort_desc'] = 		'sort_desc';

		/* name of html form */
		$this->opt['form_name'] =			'';

										
		$this->opt['fname_max_chars'] =		128;
		$this->opt['lname_max_chars'] =		128;
		$this->opt['new_uri_max_chars'] =	128;
		

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
			
			
			if ($val['new_uri'] != $new_uri or
			    $val['fname'] != $_POST['fname_'.$val['index']] or
			    $val['lname']  != $_POST['lname_'.$val['index']]){
			
				$opt = array( 'insert' => $val['empty'],
				              'primary_key' => $val['primary_key'],
							  'original_vals' => &$val);

				$values = array('dial_username' => $val['dial_username'],
				                'dial_did' => $val['dial_did'],
				                'new_uri' => $new_uri,
				                'fname' => $_POST['fname_'.$val['index']],
								'lname'  => $_POST['lname_'.$val['index']]);			
								
				if (false === $data->update_speed_dial($this->controler->user_id->get_uid(), $values, $opt)) return false;
			}
		
		}
	
	
		return array("m_sd_updated=".RawURLEncode($this->opt['instance_id']));
	}
	
	/* this metod is called always at begining */
	function init(){
		global $sess, $sess_sd_act_row, $sess_sd_sort, $sess_sd_sort_dir;
		parent::init();
		
		if (!$sess->is_registered('sess_sd_act_row')) $sess->register('sess_sd_act_row');
		if (!$sess->is_registered('sess_sd_sort'))    $sess->register('sess_sd_sort');
		if (!$sess->is_registered('sess_sd_sort_dir'))    $sess->register('sess_sd_sort_dir');
		if (!isset($sess_sd_act_row)) $sess_sd_act_row=0;
		if (!isset($sess_sd_sort)) $sess_sd_sort='from_uri';
		if (!isset($sess_sd_sort_dir)) $sess_sd_sort_dir='asc';
		
		if (isset($_GET['act_row'])) $sess_sd_act_row=$_GET['act_row'];

		if (isset($_GET['sd_order_by'])) {
			// save curent sorting column
			$sd_sort = $sess_sd_sort;
			
			// asign new value to $sess_sd_sort
			switch ($_GET['sd_order_by']){
			case "tu":
				$sess_sd_sort = 'to_uri'; break;
			case "fn":
				$sess_sd_sort = 'fname'; break;
			case "ln":
				$sess_sd_sort = 'lname'; break;
			default:
				$sess_sd_sort = 'from_uri';
			}
			$sess_sd_act_row=0;

			
			// if form was not submited
			if (!$this->was_form_submited()){
				// if $sd_sort = $sess_sd_sort change sorting direction
				if ($sd_sort == $sess_sd_sort){
					if ($sess_sd_sort_dir == 'asc')	$sess_sd_sort_dir = 'desc';
					else $sess_sd_sort_dir = 'asc';
				}
				else{
					if($this->opt['sort_asc_on_change_col']) $sess_sd_sort_dir = 'asc';
				}
			}
		}
		
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
		global $data, $lang_str, $sess_sd_act_row, $sess_sd_sort, $sess_sd_sort_dir;
		parent::create_html_form($errors);

		
		$opt=array('dial_did' => $this->opt['domain_for_requests'], 
		           'sort' => $sess_sd_sort,
		           'sort_desc' => ($sess_sd_sort_dir == 'asc' ? false : true));
				   
		$data->set_act_row($sess_sd_act_row);
		$data->set_showed_rows(10);
			
		if (false === $this->speed_dials = $data->get_speed_dials($this->controler->user_id->get_uid(), $opt)) return false;
		
		$this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
		$this->pager['pos']=$data->get_act_row();
		$this->pager['items']=$data->get_num_rows();
		$this->pager['limit']=$data->get_showed_rows();
		$this->pager['from']=$data->get_res_from();
		$this->pager['to']=$data->get_res_to();
		
		
		$i=0;
		foreach ($this->speed_dials as $key => $val){
			$index = sprintf("%02u", $i);
			/* add index into speed_dial array */
			$this->speed_dials[$key]['index'] = $index;
			
			$element_new_uri = array("type"=>"text",
			                             "name"=>"new_uri_".$index,
										 "size"=>16,
										 "maxlength"=>$this->opt['new_uri_max_chars'],
			                             "value"=>$val['new_uri']);
										 
			if ($this->opt['username_in_target_only']){
				/* parse username from request uri */
				$element_new_uri["value"]       = $this->reg->get_username($val['new_uri']);
				
				/* if user should enter only phonenumber as username part of uri, add validating regex */
				if ($this->opt['numerical_target_only']){
					$element_new_uri["valid_regex"] = "^(".$this->reg->phonenumber.")?$";
					$element_new_uri["valid_e"]     = $lang_str['fe_not_valid_phonenumber'];
				}
			}
			else{
				$element_new_uri["value"]       = $val['new_uri'];
				$element_new_uri["valid_regex"] = "^(".$this->reg->sip_address.")?$";
				$element_new_uri["valid_e"]     = $lang_str['fe_not_valid_sip'];
				$element_new_uri["extrahtml"]   = "onBlur='sip_address_completion(this)'";
			}
			
			$this->f->add_element($element_new_uri);
										 
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"fname_".$index,
										 "size"=>16,
										 "maxlength"=>$this->opt['fname_max_chars'],
			                             "value"=>$val['fname']));
										 
			$this->f->add_element(array("type"=>"text",
			                             "name"=>"lname_".$index,
										 "size"=>16,
										 "maxlength"=>$this->opt['lname_max_chars'],
			                             "value"=>$val['lname']));

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
		
		//try to call user checking function yet
		if (!empty($this->opt['validate_funct'])){
			foreach ($this->speed_dials as $key => $val){
				if (!call_user_func_array($this->opt['validate_funct'], array(&$_POST['new_uri_'.$val['index']], &$errors))){
					//user checking function returned false -> number is wrong
					return false;
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
		global $smarty, $sess, $sess_sd_sort, $sess_sd_sort_dir;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
		$smarty->assign_by_ref($this->opt['smarty_speed_dials'], $this->speed_dials);

		$smarty->assign_by_ref($this->opt['smarty_sort_from_uri'], $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&sd_order_by=fu"));
		$smarty->assign_by_ref($this->opt['smarty_sort_to_uri'], $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&sd_order_by=tu"));
		$smarty->assign_by_ref($this->opt['smarty_sort_fname'], $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&sd_order_by=fn"));
		$smarty->assign_by_ref($this->opt['smarty_sort_lname'], $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&sd_order_by=ln"));

		$smarty->assign_by_ref($this->opt['smarty_sort_by'], $sess_sd_sort);
		$smarty->assign($this->opt['smarty_sort_desc'], $sess_sd_sort_dir == 'asc' ? false : true);

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

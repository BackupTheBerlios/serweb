<?
/*
 * $Id: page_controler.php,v 1.8 2005/03/14 11:45:46 kozlik Exp $
 */ 

/*
   Configuration:
   --------------
   shared_html_form		(boolean) default:false - if is true - html form is conjunct for all html forms on the page
   smarty_form			name of smarty variable - see below - used only if shared_html_form is true
   form_name			name of html form - used only if shared_html_form is true
   form_submit			assotiative array describe submit element of shared form. 
   							For details see description of method add_submit in class form_ext
   

   Exported smarty variables:
   --------------------------
   parameters 	assigned to $page_attributes
   lang_str 	assigned to $lang_str
   lang_set 	assigned to $lang_set
   user_auth	assigned to $this->user_id - associative array containing username, domain and uuid
                        of user loged in or of user which datails admin is examining
   come_from_admin_interface    assigned to $come_from_admin_interface
   cfg			assigned to $config (contain only these properties:
					img_src_path
					js_src_path
					style_src_path
					user_pages_path
					admin_pages_path
					domains_path
   				)

   opt['smarty_form'] 			(form)			phplib html form

*/
 
class page_conroler{
	/* array of application units */
	var $apu_objects=array();
	/* file with smarty template */
	var $template_name;
	/* flag which indicated that user come from admin interface */
	var $come_from_admin_interface=false;
	/* auth info of user with which setting we are working. Usualy is same as $serweb_auth, only admin can change it */
	var $user_id = null;		
	/* associative array of controller options */
	var $opt=array();
	/* html form */
	var $f = null; 			
	/* set of apu names added to hidden form element of shared form */
	var $form_apu_names=array();
	/* flags which says if header 'location' will be send and if html form should be validated */
	var $send_header_location = false;
	var $validate_html_form = false;

	var $errors=array();
	var $messages=array();
	
	/* constructor */
	function page_conroler(){
		global $sess, $perm, $data, $serweb_auth, $sess_page_controler_user_id;

		$this->opt['shared_html_form'] =	false;

		/* form */
		$this->opt['smarty_form'] =			'form';
		/* name of html form */
		$this->opt['form_name'] =			'';
		/* form submit element */
		$this->opt['form_submit']=			array('type'=>'hidden');
		
		// get $user_id if admin want work with some setting of user
		if (isset($perm) and $perm->have_perm("admin")){
			//first try get user_id from session variable
			if (isset($sess_page_controler_user_id)){
				$this->user_id = $sess_page_controler_user_id;
				$this->come_from_admin_interface=true;
			}
		
			//second if userauth param is given, get user_id from it
			if (false !== $uid = get_userauth_from_get_param('u')) {
				if (0 > ($pp=$data->check_admin_perms_to_user($serweb_auth, $uid, $this->errors))) break;
				if (!$pp){
					die("You haven't permissions to manage user '".$uid->uname."'");
					break;
				}
				//register session variable
				if (!$sess->is_registered('sess_page_controler_user_id')) $sess->register('sess_page_controler_user_id');
		
				$this->user_id = $sess_page_controler_user_id = $uid;
				$this->come_from_admin_interface=true;
			}
			
			//if still user_id is null, get it from $serweb_auth
			if (is_null($this->user_id))
				$this->user_id=$serweb_auth;
				

		}
		else $this->user_id=$serweb_auth;
	}
	
	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('check_admin_perms_to_user');
	}

	/* add application unit to $apu_objects array*/
	function add_apu(&$class){
		$this->apu_objects[] = &$class;
	}
	
	/* set name of template */
	function set_template_name($template){
		$this->template_name = $template;
	}
	
	/* set option $opt_name to value $val */
	function set_opt($opt_name, $val){
		$this->opt[$opt_name]=$val;
	}

	/* determine actions of all application units 
	   and check if some APU needs validate form or send header 'location'
	 */
	function _determine_actions(){
		$this->send_header_location=false;
		$this->validate_html_form=false;

		foreach($this->apu_objects as $key=>$val){
			$this->apu_objects[$key]->determine_action();

			if (isset($this->apu_objects[$key]->action['reload']) and $this->apu_objects[$key]->action['reload'])
				$this->send_header_location=true;
			if (isset($this->apu_objects[$key]->action['validate_form']) and $this->apu_objects[$key]->action['validate_form'])
				$this->validate_html_form=true;

			/* if should be this APU processed alone */
			if (isset($this->apu_objects[$key]->action['alone']) and $this->apu_objects[$key]->action['alone']){
				$this->validate_html_form = isset($this->apu_objects[$key]->action['validate_form'])?$this->apu_objects[$key]->action['validate_form']:false;
				$this->send_header_location = isset($this->apu_objects[$key]->action['reload'])?$this->apu_objects[$key]->action['reload']:false;

				/* save this APU */
				$temp = &$this->apu_objects[$key];
				/* unset all other APUs */				
				$this->apu_objects = array();
				$this->add_apu($temp);
				
				break;
			}
		}
	}
	
	/* create html form by all application units */
	function _create_html_form(){
		foreach($this->apu_objects as $key=>$val){
			$this->apu_objects[$key]->create_html_form($this->errors);
		}

		if ($this->opt['shared_html_form']) {
			$this->f->add_element(array("type"=>"hidden",
			                             "name"=>"apu_name",
										 "multiple"=>true,
			                             "value"=>$this->form_apu_names));

			$this->f->add_submit($this->opt['form_submit']);
		}
	}
	
	/* validate html form */
	function _validate_html_form(){

		if ($this->validate_html_form){

			/* if is used shared html form, validate it */
			if ($this->opt['shared_html_form']) {
				if ($err = $this->f->validate()) {			// Is the data valid?
					$this->errors = array_merge($this->errors, $err); // No!
					return false;
				}
			}
			
			/* validate html form by all application units */
			foreach($this->apu_objects as $key=>$val){
				if (isset($this->apu_objects[$key]->action['validate_form']) and $this->apu_objects[$key]->action['validate_form']){
					if (false === $this->apu_objects[$key]->validate_form($this->errors)) {
						return false;
					}
				}
			}
		}
		
		return true;
	}

	/* load default values to form */
	function _form_load_defaults(){
		/* if is used shared html form, load defaults to it */
		if ($this->opt['shared_html_form']) 
			$this->f->load_defaults();
		/* otherwise load defaults to foem of each APU */
		else{
			foreach($this->apu_objects as $key=>$val){
				$this->apu_objects[$key]->f->load_defaults();
			}
		}
		
	}
	
	/** execute actions of all application units **/
	function _execute_actions(){
		global $_SERVER, $sess;
	
		$send_get_param = array();
		foreach($this->apu_objects as $key=>$val){
			/* if location header will be send, skip APUs which action['reload'] 
			   is not set - this APUs doesn't made any DB update */
			if ($this->send_header_location and 
			     !(isset($this->apu_objects[$key]->action['reload']) and $this->apu_objects[$key]->action['reload']))
			   continue;
			   
			/* call the action method */
			$_apu = &$this->apu_objects[$key];
			$_method = "action_".$this->apu_objects[$key]->action['action'];
			$_retval = call_user_func_array(array(&$_apu, $_method), array(&$this->errors));

			/* check for the error */				
			if (false === $_retval) return false;
			
			/* join GET parameters that will be send */
			if (is_array($_retval)) $send_get_param = array_merge($send_get_param, $_retval);
		}
		
		/* if header location should be send */
		if ($this->send_header_location){
			/* collect all get params to one string */
			$send_get_param = implode('&', $send_get_param);
			
			/* send header */
	        Header("Location: ".$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
								($send_get_param ? 
									'&'.$send_get_param : 
									'')));
			/* break the script execution */
			page_close();
			exit;
		}
		
		return true;
	}
	
	/** assign values and form(s) to smarty **/
	function _smarty_assign(){
		global $smarty;
		
		/** assign values to smarty **/
		foreach($this->apu_objects as $key=>$val){
			$this->apu_objects[$key]->pass_values_to_html();
		}

		/** assign html form(s) to smarty **/
		$js_before = "";
		$js_after  = "";
		foreach($this->apu_objects as $key=>$val){
			$form_array = $this->apu_objects[$key]->pass_form_to_html();

			/* if this APU didn't use html form, skip it */
			if ($form_array === false) continue;
			
			if (!isset($form_array['smarty_name'])) $form_array['smarty_name'] = '';
			if (!isset($form_array['form_name']))   $form_array['form_name'] = '';
			if (!isset($form_array['before']))      $form_array['before'] = '';
			if (!isset($form_array['after']))       $form_array['after'] = '';
			
			/* if html form is shared, collect after and before javascript from all APUs */
			if ($this->opt['shared_html_form']){
				$js_before .= $form_array['before'];
				$js_after  .= $form_array['after'];
			}
			/* otherwise create forms for all APUs */
			else {
				$smarty->assign_phplib_form($form_array['smarty_name'], 
											$this->apu_objects[$key]->f, 
											array('jvs_name'  => 'form_'.$this->apu_objects[$key]->opt['instance_id'],
											      'form_name' => $form_array['form_name']), 
											array('before'    => $form_array['before'],
											      'after'     => $form_array['after']));
			}
		}
		
		/* if html form is shared, create it */
		if ($this->opt['shared_html_form']){
			$smarty->assign_phplib_form($this->opt['smarty_form'], 
										$this->f, 
										array('jvs_name'  => 'form_by_page_controler',
										      'form_name' => $this->opt['form_name']), 
										array('before'    => $js_before,
										      'after'     => $js_after));
		
		}
	}

	function convert_htmlspecialchars(&$var){
		if (is_array($var)){
			foreach($var as $k => $v)
				$var[$k] = $this->convert_htmlspecialchars($v);
		}
		elseif (is_object($var)){
			//object shouldn't be here
			//do nothing
		}
		else{
			$var = htmlspecialchars($var, ENT_QUOTES);
		}
		return $var;
	}


	function hack_protection(){
		if (isset($_POST)){
			$_POST = $this->convert_htmlspecialchars($_POST);
		}

		if (isset($_GET)){
			$_GET = $this->convert_htmlspecialchars($_GET);
		}
	}

	
	/*****************  start processing of page *******************/
	function start(){
		global $smarty, $lang_str, $lang_set, $page_attributes, $config;

		/* translate chars '<', '>', etc. to &lt; &gt; etc.. */
		$this->hack_protection();

		if ($this->opt['shared_html_form']) $this->f = new form_ext;  // create a form object
		
		/* propagate user_id and reference to this to all application units */
		foreach($this->apu_objects as $key=>$val){
			$this->apu_objects[$key]->user_id=$this->user_id;
			$this->apu_objects[$key]->controler=&$this;
		}

		/* run all init methods */
		foreach($this->apu_objects as $key=>$val){
			$this->apu_objects[$key]->init();
		}

		/* determine actions of all application units 
		   and check if some APU needs validate form or send header 'location'
		 */
		$this->_determine_actions();
	
		/* create html form by all application units */
		$this->_create_html_form();
		
		/* validate html form */
		$form_valid = $this->_validate_html_form();
		
		/* if form(s) valid, execute actions of all application units */
		if ($form_valid)
			$this->_execute_actions();
		/* otherwise load defaults to the form(s) */
		else
			$this->_form_load_defaults();

		/** get messages **/
		foreach($this->apu_objects as $key=>$val){
			$this->apu_objects[$key]->return_messages($this->messages);
		}

		/** assign values and form(s) to smarty **/
		$this->_smarty_assign();

		$smarty->assign_by_ref('parameters', $page_attributes);
		$smarty->assign_by_ref('lang_str', $lang_str);
		$smarty->assign_by_ref('lang_set', $lang_set);
		$smarty->assign_by_ref('user_auth', $this->user_id);
		$smarty->assign_by_ref('come_from_admin_interface', $this->come_from_admin_interface);

		
		$cfg=new stdclass();
		$cfg->img_src_path = 		$config->img_src_path;
		$cfg->js_src_path =    		$config->js_src_path;
		$cfg->style_src_path = 		$config->style_src_path;
		$cfg->user_pages_path = 	$config->user_pages_path;
		$cfg->admin_pages_path =	$config->admin_pages_path;
		$cfg->domains_path =		$config->domains_path;
		$smarty->assign_by_ref("cfg", $cfg);		
		
		//page atributes - get user real name
		
		$page_attributes['errors']=&$this->errors;	
		$page_attributes['message']=&$this->messages;
		
		/* obtain list of required javascripts */
		$required_javascript=array();
		foreach($this->apu_objects as $val){
			$required_javascript = array_merge($required_javascript, $val->get_required_javascript());
		}
		$required_javascript = array_unique($required_javascript);
		

		/* ----------------------- HTML begin ---------------------- */
		print_html_head();
		
		foreach($required_javascript as $val){
?><script language="JavaScript" src="<?echo $config->js_src_path.$val;?>"></script><?			 
		}
		print_html_body_begin($page_attributes);
				
		$smarty->display($this->template_name);

		print_html_body_end();
		echo "</html>\n";
		page_close();
	}
}
?>

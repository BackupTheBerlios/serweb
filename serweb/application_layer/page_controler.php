<?
/**
 * Page controler
 * 
 * @author    Karel Kozlik
 * @version   $Id: page_controler.php,v 1.29 2006/09/22 09:26:52 kozlik Exp $
 * @package   serweb
 */ 

/**
 *	Page controler
 *	
 *	Configuration:
 *	--------------
 *	currently none   
 *	
 *	 
 *	Exported smarty variables:
 *	--------------------------
 *	parameters 	assigned to $page_attributes
 *	lang_str 	assigned to $lang_str
 *	lang_set 	assigned to $lang_set
 *	user_auth	assigned to $this->user_id - associative array containing username, domain and uuid
 *	                     of user loged in or of user which datails admin is examining
 *	come_from_admin_interface    assigned to $come_from_admin_interface
 *	cfg			assigned to $config (contain only these properties:
 *					img_src_path
 *					js_src_path
 *					style_src_path
 *					user_pages_path
 *					admin_pages_path
 *					domains_path
 *				)
 *	
 *	Smarty variables containing html forms. For details see methods 
 *	assign_form_name() and set_name_of_smarty_var_for_form().
 *	
 */
 
class page_conroler{
	/** array of application units */
	var $apu_objects=array();
	/** file with smarty template */
	var $template_name="_default.tpl";
	/** flag which indicated that user come from admin interface */
	var $come_from_admin_interface=false;

	/** auth info of user with which setting we are working. 
	 *	Usualy is same as $_SESSION['auth']->get_logged_user(), only admin can change it 
	 */
	var $user_id = null;		

	/** id of domain with which setting we are working. 
	 *	Only admin can change it 
	 */
	var $domain_id = null;		

	/** associative array of controller options */
	var $opt=array();
	/** array of html forms */
	var $f = array(); 			
	/** flags which says if header 'location' will be send and if html form should be validated */
	var $send_header_location = false;
	/** flag if should be some html form validated */
	var $validate_html_form = false;
	/** flag if is used html form */
	var $shared_html_form = false;
	/** array contain required javascript files */
	var	$required_javascript = array();
	/** flag - is timezone already set? */
	var $is_set_timezone = null;
	/** js which should be placed after document body */
	var $js_after_document = array();
	/** url to which header location will redirect - default is self */
	var $url_for_reload = null;

	/** for backward compatibility - Hack protect will be removed - by setting this var to true may be enabled for single page*/
	var $perform_hack_protect = false;

	/** instance of Creg class 
	 *	for backward compatibility, shouldn't be used
	 *	instead of this use your own variable and Creg::singleton method
	 *	@deprec
	 */
	var $reg;

	/** flag if the check to permissions to user should be performed */
	var $check_perms_to_user = false;
	/** flag if the check to permissions to domain should be performed */
	var $check_perms_to_domain = false;
	var $errors=array();
	var $messages=array();
	/** list of interapu vars */
	var $interapu_vars = array();
	/** reference to container inside session variable */
	var $session;
	
	/* constructor */
	function page_conroler(){

		$this->reg = Creg::singleton();				// create regular expressions class

		$eh = &ErrorHandler::singleton();
		$eh -> set_errors_ref($this->errors);

		$this->session_init();
		$this->xxl_init();
		$this->init_this_uid_and_did();
		$this->set_interapu_vars();
		
		// if $data_selected_proxy was not set, reference it to $data_auth
		if (!isset($GLOBALS['data_selected_proxy']) or !$GLOBALS['data_selected_proxy']) 
			$GLOBALS['data_selected_proxy'] = &$GLOBALS['data_auth'];
	}

	function session_init(){
	
		/* create container in session variable if does not exists */
		if (!isset($_SESSION['page_ctl'])) $_SESSION['page_ctl'] = array();
		
		$this->session = &$_SESSION['page_ctl'];
	}

	/**
	 *	Initialy set $this->user_id and $this->domain_id
	 */
	function init_this_uid_and_did(){
		global $perm;

		// get $user_id if admin want work with some setting of user
		if (isset($perm) and $perm->have_perm("admin")){
			$this->init_this_uid();
			$this->init_this_did();
		}
 		else {
 			if (!empty($_SESSION['auth']))
 				$this->user_id = $_SESSION['auth']->get_logged_user();
 			else
 				$this->user_id = null;
 		}
	}

	/**
	 *	Initialy set $this->user_id
	 */
	function init_this_uid(){
		global $sess, $sess_page_controler_user_id;

		//first try get user_id from session variable
		if (isset($sess_page_controler_user_id)){
			$this->user_id = $sess_page_controler_user_id;
			$this->come_from_admin_interface=true;
		}
	
		//second if userauth param is given, get user_id from it
		if (!empty($_GET[$this->ch_user_param_name()])) {
			$uid = &SerwebUser::recreate_from_get_param($_GET[$this->ch_user_param_name()]);

			if (is_a($uid, 'SerwebUser')){
				$this->check_perms_to_user = true;

				//register session variable
				if (!$sess->is_registered('sess_page_controler_user_id')) $sess->register('sess_page_controler_user_id');
		
				$this->user_id = $sess_page_controler_user_id = $uid;
				$this->come_from_admin_interface=true;
			}
		}
		
		//if still user_id is null, get it from $_SESSION['auth'] object
		if (is_null($this->user_id) and isset($_SESSION['auth']) and is_a($_SESSION['auth'], "Auth"))
			$this->user_id=$_SESSION['auth']->get_logged_user();
	}

	/**
	 *	Initialy set $this->domain_id
	 */
	function init_this_did(){
		global $sess_page_controler_domain_id;

		/* get id of administrated domain */
		//first try get domain id from session variable
		if (isset($sess_page_controler_domain_id)){
			$this->domain_id = $sess_page_controler_domain_id;
		}

		//second if domain_id param is given, get domain id from it
		if (isset($_GET['pc_domain_id'])){
			$this->check_perms_to_domain = true;

			$this->set_domain_id($_GET['pc_domain_id']);
		}
	}

	function xxl_init(){
		global $sess, $sess_xxl_selected_proxy, $perm;
	
		// register session variable sess_xxl_selected_proxy
		if (!$sess->is_registered('sess_xxl_selected_proxy')) $sess->register('sess_xxl_selected_proxy');
		if (!isset($sess_xxl_selected_proxy)) $sess_xxl_selected_proxy=null;

		
		// get $user_id if admin want work with some setting of user
		if (isset($perm) and $perm->have_perm("admin")){
			
			// set initial value of sess_xxl_selected_proxy to same proxy where user has account
			if (isModuleLoaded('xxl') and is_null($sess_xxl_selected_proxy)){
				if (false !== $proxy_uri = $GLOBALS['data_auth']->get_home_proxy($this->errors)){
					$sess_xxl_selected_proxy = array('proxy' => $proxy_uri);
				}
			}

			// in XXL version and if is set sess_xxl_selected_proxy
			// create new instance of data_layer binded to seleceted proxy
			// note that data_layer binded to currently logged user is still 
			// in variable $data_auth
			if (isModuleLoaded('xxl') and $sess_xxl_selected_proxy){
				$GLOBALS['data_selected_proxy'] = CData_Layer::singleton("selected_proxy", $this->errors);
				$GLOBALS['data_selected_proxy']->setup_by_another_instance($GLOBALS['data_auth']);
				if (false === $GLOBALS['data_selected_proxy']->set_home_proxy($sess_xxl_selected_proxy['proxy'])){
					die("Can't connect to DB");
					break;
				}
				$GLOBALS['data'] = &$GLOBALS['data_selected_proxy'];
			}
		}
	}

	/**
	 *	Return name of get param used to change user
	 *	
	 *	@return string
	 */
	function ch_user_param_name(){
		return "ctl_user";
	}

	/**
	 *	return string which can be used as $_GET param containing id of domain
	 *
	 *	@param 	string $domain_id		id of domain
	 *	@return string					$_GET param
	 */
	function domain_to_get_param($domain_id){
		return "pc_domain_id=".RawURLEncode($domain_id);
	}
	
	/**
	 *	set $this->domain_id and session variable to given value
	 *
	 *	@param 	string $domain_id		id of domain
	 */
	function set_domain_id($domain_id){
		global $sess, $sess_page_controler_domain_id;

		//register session variable
		if (!$sess->is_registered('sess_page_controler_domain_id')) $sess->register('sess_page_controler_domain_id');
		
		$this->domain_id = $sess_page_controler_domain_id = $domain_id;
	}

	/**
	 *	set $this->user_id and session variable to given value
	 *
	 *	@param 	SerwebUser $user_id		
	 */
	function set_user_id($user_id){
		global $sess, $sess_page_controler_user_id;

		//register session variable
		if (!$sess->is_registered('sess_page_controler_user_id')) $sess->register('sess_page_controler_user_id');
		
		$this->user_id = $sess_page_controler_user_id = $user_id;
	}

	/**
	 * return GET param which set interapu variable named $name to given $value
	 * @return string
	 */
	function get_interapu_url_param($name, $value){
		return "pctlia_".$name."=".RawURLEncode($value);
	}

	/**
	 * return value of interapu variable
	 * @return mixed
	 */
	function get_interapu_var($name){
		if (isset($this->session['interapu'][$name])) return $this->session['interapu'][$name];
		else return null;
	}

	/**
	 * set value of interapu variable
	 * @access private
	 */
	function set_interapu_var($name, $value){
		$this->session['interapu'][$name] = $value;
	}
	
	/**
	 * set interapu variables by GET params
	 * @access private
	 */
	function set_interapu_vars(){
		foreach ($this->interapu_vars as $v){
			if (isset($_GET['pctlia_'.$v])) $this->session['interapu'][$v] = $_GET['pctlia_'.$v];
		}
	}
	
	/**
	 * return required data layer methods - static class 
	 * @static
	 */
	function get_required_data_layer_methods(){
		return array('check_admin_perms_to_user', 'check_admin_perms_to_domain');
	}

	/* add application unit to $apu_objects array*/
	function add_apu(&$class){
		$this->apu_objects[] = &$class;
	}
	
	/* set nam1e of template */
	function set_template_name($template){
		$this->template_name = $template;
	}
	
	/* set option $opt_name to value $val */
	function set_opt($opt_name, $val){
		$this->opt[$opt_name]=$val;
	}

	/**
	 *	add massage which will be displayed on page 
	 *
	 *	@param array $msg	message - associative array with keys 'short' and 'long'
	 */
	function add_message($msg){
		$this->messages[] = &$msg;
	}

	/**
	 *	set timezone which is used by date/time formating function to timezone 
	 *  of user
	 *
	 *	@param string $uid	user to which timezone should be set - if not given $this->user_id is used
	 */
	function set_timezone($uid = null){
		global $config;
		if (is_null($uid)) $uid = $this->user_id->get_uid();

		$an = &$config->attr_names;

		/* if timezone is already set for this user, do not set it again */
		if (is_null($this->is_set_timezone) or $this->is_set_timezone != $uid){

			$o = array('uid' => $uid);
			if (false === $tz = Attributes::get_attribute($an['timezone'], $o)) return false;

			if (!is_null($tz)){
				putenv("TZ=".$tz); //set timezone
				$this->is_set_timezone = $uid;
			}
		}
		
		return true;
	}

	function set_onload_js($js){
		$this->js_after_document[]=$js;
	}

	/**
	 *	Do not check if admin have perms to manage user
	 *	@access public
	 */
	function do_not_check_perms_of_admin(){
		$this->check_perms_to_user = false;
	}

	/**
	 *	Change url to which browser will be redirected by location header
	 *
	 *	Important: this method should be called _ONLY_ from methods action_*()
	 *	of APU
	 *
	 *	@param string $url 
	 */
	function change_url_for_reload($url){
		$this->url_for_reload = $url;
	}

	/**
	 *	Add file to set of required javascript files
	 *	
	 *	@param string $file name of javascript file
	 */
	function add_reqired_javascript($file){
		$this->required_javascript[] = $file;
	}

	/**
	 *	Create new shared html form if still not exists and assign APU to it
	 *
	 *	@param string $form_name	name of html form
	 *	@param object $apu			instance of APU
	 */	
	function assign_form_name($form_name, &$apu){
		global $lang_str, $config, $sess_lang;
		/* we are useing shared html forms */
		$this->shared_html_form = true; 
		/* if form of this name still not exists, create initial values */
		if (!isset($this->f[$form_name])){
			$this->f[$form_name]['validate'] = false;					// should be this form validated?
			$this->f[$form_name]['submit'] = array('type' => 'image',	// submit element
													'text' => $lang_str['b_submit'],
													'src'  => get_path_to_buttons("btn_submit.gif", $sess_lang));

			$this->f[$form_name]['smarty_name'] = 'form_'.$form_name;	// name of smarty variable
			$this->f[$form_name]['form'] = new form_ext();  			// form object
			$this->f[$form_name]['apu_names'] = array();				// set of apu names added to hidden form element of this form
			$this->f[$form_name]['js_before'] = "";
			$this->f[$form_name]['js_after'] = "";
			$this->f[$form_name]['msg_apu'] = array($apu->opt['instance_id']);	//APUs from which are messages displayed - default first APU assigned to this form
		}
		$this->f[$form_name]['apu'][] = &$apu;
		$apu->form_name = $form_name;
	}
	
	/**
	 *	Change name of smarty variable used for html form
	 *
	 *	@param string $form_name	name of existing html form
	 *	@param string $smarty_var	name of smarty variable - default value is form_&lt;$form_name&gt;
	 *	@return bool				FALSE if form with given name still not exists, TRUE otherwise
	 */	
	function set_name_of_smarty_var_for_form($form_name, $smarty_var){
		if (!isset($this->f[$form_name])){
			sw_log("Form with name '".$form_name."' is not set. Use method assign_form_name() first.", PEAR_LOG_DEBUG);	
			return false;
		}
		
		$this->f[$form_name]['smarty_name'] = $smarty_var;
		return true;
	}

	/**
	 *	Change submit element for html form
	 *
	 *	Example creating submit element:
	 *	<code>
	 *		array('type' => 'image',
	 *			  'text' => $lang_str['b_submit'],
	 *			  'src'  => get_path_to_buttons("btn_submit.gif", $sess_lang))
	 *	</code>
	 *
	 *	@param string $form_name	name of existing html form
	 *	@param array  $submit		assotiative array describe submit element of shared form. For details see description of method add_submit in class form_ext
	 *	@return bool				FALSE if form with given name still not exists, TRUE otherwise
	 */	
	function set_submit_for_form($form_name, $submit){
		if (!isset($this->f[$form_name])){
			sw_log("Form with name '".$form_name."' is not set. Use method assign_form_name() first.", PEAR_LOG_DEBUG);	
			return false;
		}
		
		$this->f[$form_name]['submit'] = $submit;
		return true;
	}

	/**
	 *	Select APUs from which will be displayed messages 
	 *
	 *	@param string $form_name	name of existing html form
	 *	@param array $apu_id		array of instance_ids of APUs from which may be displayed messages
	 *	@return bool				FALSE if form with given name still not exists, TRUE otherwise
	 */	
	function set_apu_for_msgs($form_name, $apu_id){
		if (!isset($this->f[$form_name])){
			sw_log("Form with name '".$form_name."' is not set. Use method assign_form_name() first.", PEAR_LOG_DEBUG);	
			return false;
		}
		
		$this->f[$form_name]['msg_apu'] = $apu_id;
		return true;
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
			if (isset($this->apu_objects[$key]->action['validate_form']) and $this->apu_objects[$key]->action['validate_form']){
				$this->validate_html_form=true;

				if ($this->shared_html_form) {
					$this->f[$val->form_name]['validate'] = true;
				}
			}

			/* if should be this APU processed alone */
			if (isset($this->apu_objects[$key]->action['alone']) and $this->apu_objects[$key]->action['alone']){
				/* set send_header_location and validate_html_form flags according to action array of this apu */
				$this->send_header_location = isset($this->apu_objects[$key]->action['reload'])?$this->apu_objects[$key]->action['reload']:false;
				$this->validate_html_form = isset($this->apu_objects[$key]->action['validate_form'])?$this->apu_objects[$key]->action['validate_form']:false;

				/* set validate flag to all forms false */
				if ($this->shared_html_form) {
					foreach($this->f as $fk => $fv){
						$this->f[$fk]['validate'] = false;
					}
					/* only to form vith this apu set validate flag according to action['validate_form'] */
					$this->f[$val->form_name]['validate'] = $this->validate_html_form;
				}

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

		if ($this->shared_html_form) {
			foreach($this->f as $key => $val){
				$this->f[$key]['form']->add_element(array("type"=>"hidden",
			                             "name"=>"apu_name",
										 "multiple"=>true,
			                             "value"=>$this->f[$key]['apu_names']));

				$this->f[$key]['form']->add_submit($this->f[$key]['submit']);
			}
		}
	}
	
	/* validate html form */
	function _validate_html_form(){

		if ($this->validate_html_form){

			/* if is used shared html form, valk throught all forms and validate them */
			if ($this->shared_html_form) {
				foreach($this->f as $key=>$val){
					if ($val['validate']){
						if ($err = $this->f[$key]['form']->validate()) {			// Is the data valid?
							$this->errors = array_merge($this->errors, $err); // No!
							return false;
						}
					}
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

	/* call form_invalid method of each apu */
	function _form_invalid(){
		foreach($this->apu_objects as $key=>$val){
			$this->apu_objects[$key]->form_invalid();
		}
	}

	/* load default values to form */
	function _form_load_defaults(){
		/* if is used shared html form, load defaults to forms which were submited */
		if ($this->shared_html_form) {
			foreach($this->f as $key=>$val){
				if (isset($_POST['apu_name']) and 
				    count(array_intersect($_POST['apu_name'], $this->f[$key]['apu_names']))){
				
					$this->f[$key]['form']->load_defaults();
				}
			}
		}
		/* otherwise load defaults to form of APU which form has been submited*/
		else{
			foreach($this->apu_objects as $key=>$val){
				if ($val->was_form_submited()) $this->apu_objects[$key]->f->load_defaults();
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
			if (!$this->url_for_reload) $this->url_for_reload = $_SERVER['PHP_SELF'];

			$param_separator = strpos($this->url_for_reload, "?") !== false ?  "&" : "?";
			
	        Header("Location: ".$sess->url($this->url_for_reload.$param_separator."kvrk=".uniqID("").
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
			if ($this->shared_html_form){
				$this->f[$val->form_name]['js_before'] .= $form_array['before'];
				$this->f[$val->form_name]['js_after'] .= $form_array['after'];
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
		if ($this->shared_html_form){
			foreach($this->f as $key=>$val){
				$smarty->assign_phplib_form($val['smarty_name'], 
											$this->f[$key]['form'], 
											array('jvs_name'  => 'form_'.$key,
											      'form_name' => $key), 
											array('before'    => $this->f[$key]['js_before'],
											      'after'     => $this->f[$key]['js_after']));
			}
		
		}
	}

	function _get_messages(){
		$apu_for_display_mesgs = array();

		// if useing shared html form, make list of APUs from which will be displayed messages
		if ($this->shared_html_form){
			foreach($this->f as $k => $v){
				$apu_for_display_mesgs = array_merge($apu_for_display_mesgs, $v['msg_apu']);
			}
			$apu_for_display_mesgs = array_unique($apu_for_display_mesgs);
		}

		//walk trought all APUs
		foreach($this->apu_objects as $key=>$val){
			$tmp_arr = array();
			// get messages from each APU
			$this->apu_objects[$key]->return_messages($tmp_arr);
			
			// assign message to output only if shared html form isn't used 
			// or if APU is among ones in $apu_for_display_mesgs
			if(!$this->shared_html_form or 
				in_array($val->opt['instance_id'], $apu_for_display_mesgs)){
				$this->messages = array_merge($this->messages, $tmp_arr);
			}
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

	/**
	 *	Check if admin has permissions to manage the user
	 *
	 *	ID of user is stored in $this->user_id variable
	 *	
	 *	@return	bool
	 */
	function check_perms_to_user(){
		global $perm, $sess_page_controler_user_id;
		
		if (!(isset($perm) and $perm->have_perm("hostmaster"))){
			$pp=$GLOBALS['data']->check_admin_perms_to_user($_SESSION['auth'], $this->user_id, array());

			if (0 > $pp or !$pp){
				if (0 > $pp) echo "Permission check error \n";

				$sess_page_controler_user_id = null;
				return false;
			} 
		}
		return true;
	}
	
	/**
	 *	Check if admin has permissions to manage the domain
	 *
	 *	ID of domain is stored in $this->domain_id variable
	 *	
	 *	@return	bool
	 */
	function check_perms_to_domain(){
		global $perm, $sess_page_controler_domain_id;
		
		if (!(isset($perm) and $perm->have_perm("hostmaster"))){
			$pp=$GLOBALS['data']->check_admin_perms_to_domain($_SESSION['auth'], $this->domain_id, array());

			if (0 > $pp or !$pp){
				if (0 > $pp) echo "Permission check error \n";

				$sess_page_controler_domain_id = null;
				return false;
			} 
		}
		return true;
	}
	
	/*****************  start processing of page *******************/
	function start(){
		global $smarty, $lang_str, $lang_set, $page_attributes, $config, $sess;
		global $sess_page_controler_domain_id;

		/* check if admin have perms to manage user */
		if ($this->check_perms_to_user){
			if (! $this->check_perms_to_user()){
				page_close();			
 				die("You haven't permissions to manage user '".$this->user_id->get_username()."@".$this->user_id->get_realm()."'");
			}
		}

		/* check if admin have perms to manage domain */
		if ($this->check_perms_to_domain){
			if (! $this->check_perms_to_domain()){
				page_close();			
				die("You haven't permissions to manage domain with id:'".$this->domain_id."'");
			}
		}
		
		/* do not allow change parameters of default domain */
		if ($this->domain_id == '0'){
			$sess_page_controler_domain_id = null;
			page_close();			
			die("Change parameters of default domain is not possible");
		}

		/* make code portable - if magic_quotes_gpc is set to on, strip slashes */
		if (get_magic_quotes_gpc()) {
		   function stripslashes_deep($value){
		       $value = is_array($value) ?
		                   array_map('stripslashes_deep', $value) :
		                   stripslashes($value);
		
		       return $value;
		   }
		
		   $_POST = array_map('stripslashes_deep', $_POST);
		   $_GET = array_map('stripslashes_deep', $_GET);
		   $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
		}

		/* translate chars '<', '>', etc. to &lt; &gt; etc.. */
		if ($this->perform_hack_protect) $this->hack_protection();

		/* propagate user_id and reference to this to all application units */
		foreach($this->apu_objects as $key=>$val){
			$this->apu_objects[$key]->user_id=$this->user_id;
			$this->apu_objects[$key]->domain_id=$this->domain_id;
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
		else {
			$this->_form_invalid();
			$this->_form_load_defaults();
		}

		/** get messages **/
		$this->_get_messages();

		/** assign values and form(s) to smarty **/
		$this->_smarty_assign();

		$smarty->assign_by_ref('parameters', $page_attributes);
		$smarty->assign_by_ref('lang_str', $lang_str);
		$smarty->assign_by_ref('lang_set', $lang_set);
		$smarty->assign_by_ref('come_from_admin_interface', $this->come_from_admin_interface);

		if (is_object($this->user_id)){
			$smarty->assign_by_ref('user_auth', $this->user_id->to_smarty());
		}

		
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
		foreach($this->apu_objects as $val){
			$this->required_javascript = array_merge($this->required_javascript, $val->get_required_javascript());
		}
		$this->required_javascript = array_unique($this->required_javascript);
		

		/* ----------------------- HTML begin ---------------------- */
		print_html_head($page_attributes);
		
		foreach($this->required_javascript as $val){
?><script language="JavaScript" src="<?echo $sess->url($config->js_src_path.$val); ?>"></script>
<?			 
		}
		print_html_body_begin($page_attributes);
				
		$smarty->display($this->template_name);

		print_html_body_end($page_attributes);
		
		
		if (count($this->js_after_document)){
			echo "\n<script language=\"JavaScript\">\n<!--\n";
			foreach($this->js_after_document as $v){
				echo $v;
			}
			echo "\n// -->\n</script>\n";
		}
		
		echo "</html>\n";
		page_close();
	}
}
?>

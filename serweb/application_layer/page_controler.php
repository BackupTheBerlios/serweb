<?
/*
 * $Id: page_controler.php,v 1.1 2004/08/25 10:19:48 kozlik Exp $
 */ 

/*
   Exported smarty variables:
   --------------------------
   parameters 	assigned to $page_attributes
   lang_str 	assigned to $lang_str
   user_auth	assigned to $this->user_id - associative array containing username, domain and uuid
                        of user loged in or of user which datails admin is examining
   come_from_admin_interface    assigned to $come_from_admin_interface
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

	var $errors=array();
	var $messages=array();
	
	/* constructor */
	function page_conroler(){
		global $sess, $perm, $data, $serweb_auth, $sess_page_controler_user_id;
		
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
	function add_apu($class){
		$this->apu_objects[] = $class;
	}
	
	/* set name of template */
	function set_template_name($template){
		$this->template_name = $template;
	}
	
	/* start processing of page */
	function start(){
		global $smarty, $lang_str, $page_attributes, $config;

		do{		
	
			/* propagate user_id to all application units */
			foreach($this->apu_objects as $key=>$val){
				$this->apu_objects[$key]->user_id=$this->user_id;
			}

			/* run all init methods */
			foreach($this->apu_objects as $key=>$val){
				$this->apu_objects[$key]->init();
			}
	
			/* determine actions of all application units */
			foreach($this->apu_objects as $key=>$val){
				$this->apu_objects[$key]->determine_action();
			}
		
			/* execute of all application units */
			foreach($this->apu_objects as $key=>$val){
				$this->apu_objects[$key]->execute($this->errors);
			}
	
			/* get messages */
			foreach($this->apu_objects as $key=>$val){
				$this->apu_objects[$key]->return_messages($this->messages);
			}
			
			/* assign values to smarty */
			foreach($this->apu_objects as $key=>$val){
				$this->apu_objects[$key]->pass_values_to_html();
			}
		}while(false);

		$smarty->assign_by_ref('parameters', $page_attributes);
		$smarty->assign_by_ref('lang_str', $lang_str);
		$smarty->assign_by_ref('user_auth', $this->user_id);
		$smarty->assign_by_ref('come_from_admin_interface', $this->come_from_admin_interface);

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
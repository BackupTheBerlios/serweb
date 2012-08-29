<?php
/**
 *	Application unit subscribers
 *	
 *	@author     Karel Kozlik
 *	@version    $Id: apu_subscribers.php,v 1.16 2012/08/29 16:06:44 kozlik Exp $
 *	@package    serweb
 *	@subpackage mod_subscribers
 */ 

/** 
 *	Application unit subscribers 
 *
 *	This application unit is used for get list of subscribers and looking for subscribers
 *	   
 *	<pre>
 *	Configuration:
 *	--------------
 *	
 *	'use_chk_adminsonly'		(bool) default: false
 *	 set to true if you want use checkbox 'adminsonly' for display only admins
 *	
 *	'use_chk_onlineonly'		(bool) default: false
 *	 set to true if you want use checkbox 'onlineonly' for display only online users
 *	
 *	'def_chk_adminsonly'		(bool) default: false
 *	 set to true if checkbox 'adminsonly' should be initialy checked
 *	
 *	'def_chk_onlineonly'		(bool) default: false
 *	 set to true if checkbox 'onlineonly' should be initialy checked
 *	
 *	'only_from_administrated_domains'	(bool) default: false
 *	 set to true for display only users from domains administrated by admin
 *	
 *	'get_user_list'  			(bool) default: true
 *	 determines whether APU should retrieve list of users on default action
 *	
 *	'get_user_aliases'			(bool) default: false
 *	 set to true if you want display aliases of users
 *	
 *	'get_user_sip_uri'			(bool) default: false
 *	 set to true if you want display sip uri of users
 *	
 *	'get_timezones'				(bool) default: false
 *	 set to true if you want display timezone of users
 *	
 *	'get_only_agreeing'			(bool) default: false
 *	 set to true if you want limit result of founded users to only them 
 *	 which agree with it (attribute allow_find = 1)
 *	
 *	'get_credentials'			(bool) default: false
 *	 set to true if you want display credentials of users
 *	
 *	'get_disabled'				(bool) default: true
 *	 if true, disabled users are also displayed
 *	
 *	'allow_edit'				(bool) default: false
 *   set true if instance of this APU should be used for change values
 *	 by default only get list of subscribers is enabled
 *
 *	'url_after_self_delete'		(string) default: ''
 *	 URL to which will be browser redirected after user self delete his/her account. 
 *	
 *	'script_phonebook'			(string) default: ''
 *	 Name of script with phonebook. If is set, array of users will contain 
 *	 field 'url_add_to_pb' which is url for add subscriber to phonebook.
 *	
 *	'sess_seed'					(int or string) default:0
 *	 this is used for distinguish session variables of multiple instances of this
 *	 APU on more pages. If you are useing this APU on more places, set for each
 *	 instance different 'sess_seed'
 *	
 *	
 *	'msg_delete'				default: $lang_str['msg_user_deleted_s'] and $lang_str['msg_user_deleted_l']
 *	 message which should be showed on attributes update - assoc array with keys 'short' and 'long'
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
 *	  'was_deleted' - when subscriber was deleted
 *
 *	opt['smarty_pager']				(pager)
 *	 associative array containing size of result and which page is returned
 *	
 *	opt['smarty_subscribers'] 		(users)	
 *	 associative array containing subscribers
 *	 The array have same keys as function get_users (from data layer) returned. 
 *	
 *	</pre>
 *	@package    serweb
 *	@subpackage mod_subscribers
 */

class apu_subscribers extends apu_base_class{
	var $smarty_action='default';
	var $js_before='';

	var $sorter=null;
	var $filter=null;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_users', 'mark_user_deleted', 'delete_sip_user',
		             'enable_user', 'check_admin_perms_to_user');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array('sip_address_completion.js.php');
	}
	
	/* constructor */
	function apu_subscribers(){
		global $lang_str, $sess_lang;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['use_chk_adminsonly'] =			false;
		$this->opt['def_chk_adminsonly'] =			false;
		$this->opt['use_chk_onlineonly'] =			false;
		$this->opt['def_chk_onlineonly'] =			false;

		$this->opt['sess_seed'] =	null;

		$this->opt['only_from_administrated_domains'] = false;

		$this->opt['get_user_list']			= true;
		$this->opt['get_user_aliases']		= false;
		$this->opt['get_user_sip_uri']		= false;
		$this->opt['get_timezones']			= false;
		$this->opt['get_only_agreeing']		= false;
		$this->opt['get_disabled']			= true;
		$this->opt['get_credentials']		= false;

		$this->opt['allow_edit']			= false;

		$this->opt['perm_purge']			= false;
		$this->opt['perm_undelete']			= false;
		$this->opt['perm_display_deleted']	= false;

		$this->opt['url_after_self_delete'] = '';
		
		$this->opt['script_phonebook'] =			'';

		/* message on attributes update */
		$this->opt['msg_delete']['short'] =	&$lang_str['msg_user_deleted_s'];
		$this->opt['msg_delete']['long']  =	&$lang_str['msg_user_deleted_l'];
		$this->opt['msg_undelete']['short'] =	&$lang_str['msg_user_undeleted_s'];
		$this->opt['msg_undelete']['long']  =	&$lang_str['msg_user_undeleted_l'];
		$this->opt['msg_purge']['short'] =	&$lang_str['msg_user_purged_s'];
		$this->opt['msg_purge']['long']  =	&$lang_str['msg_user_purged_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* pager */
		$this->opt['smarty_pager'] =		'pager';

		$this->opt['smarty_subscribers'] = 	'users';

		$this->opt['smarty_url_self_delete'] =   'url_self_delete';

		/* name of html form */
		$this->opt['form_name'] =			'';
		
		$this->opt['form_submit']=array('type' => 'image',
										'text' => $lang_str['b_find'],
										'src'  => get_path_to_buttons("btn_find.gif", $sess_lang));
		
	}

	function set_filter(&$filter){
		$this->filter = &$filter;
	}

	function set_sorter(&$sorter){
		$this->sorter = &$sorter;
	}

	/**
	 *	this metod is called always at begining - initialize variables
	 */
	function init(){
		global $sess;
		parent::init();

        $session_name = !is_null($this->opt['sess_seed']) ? 
                        $this->opt['sess_seed'] :
                        $this->opt['instance_id'];

		if (!isset($_SESSION['apu_subscribers'][$session_name])){
			$_SESSION['apu_subscribers'][$session_name] = array();
		}
		
		$this->session = &$_SESSION['apu_subscribers'][$session_name];

		if (is_a($this->sorter, "apu_base_class")){
			/* register callback called on sorter change */
			$this->sorter->set_opt('on_change_callback', array(&$this, 'sorter_changed'));
			$this->sorter->set_base_apu($this);
		}

		if (is_a($this->filter, "apu_base_class")){
			$this->filter->set_base_apu($this);
		}
	}

	/**
	 *	callback function called when sorter is changed
	 */
	function sorter_changed(){
		if (is_a($this->filter, "apu_base_class")){
			$this->filter->set_act_row(0);
		}
	}

	function get_sorter_columns(){
		return array('username', 'realm', 'fname', 'lname', 'name', 
		             'phone', 'email', 'uid');
	}

	function get_filter_form(){
		global $lang_str;
		
		$f = array();

		$f[] = array("type"=>"text",
		             "name"=>"uid",
		             "maxlength"=>64,
					 "label"=>$lang_str['ff_uid']);

		$f[] = array("type"=>"text",
		             "name"=>"username",
		             "maxlength"=>64,
					 "label"=>$lang_str['ff_username']);

		$f[] = array("type"=>"text",
		             "name"=>"fname",
		             "maxlength"=>255,
					 "label"=>$lang_str['ff_first_name']);

		$f[] = array("type"=>"text",
		             "name"=>"lname",
		             "maxlength"=>255,
					 "label"=>$lang_str['ff_last_name']);

		$f[] = array("type"=>"text",
		             "name"=>"email",
		             "maxlength"=>255,
					 "label"=>$lang_str['ff_email']);

		$f[] = array("type"=>"text",
		             "name"=>"domain",
		             "maxlength"=>128,
					 "label"=>$lang_str['ff_domain']);

		$f[] = array("type"=>"text",
		             "name"=>"alias",
		             "maxlength"=>255,
					 "label"=>$lang_str['ff_alias']);

		$f[] = array("type"=>"text",
		             "name"=>"sipuri",
		             "maxlength"=>255,
					 "label"=>$lang_str['ff_sip_address']);

		if ($this->opt['use_chk_onlineonly']){
    		$f[] = array("type"=>"checkbox",
    		             "name"=>"onlineonly",
    					 "label"=>$lang_str['ff_show_online_only'],
    					 "initial"=>$this->opt['def_chk_onlineonly']);
    	}

		if ($this->opt['use_chk_adminsonly']){
    		$f[] = array("type"=>"checkbox",
    		             "name"=>"adminsonly",
    					 "label"=>$lang_str['ff_show_admins_only'],
    					 "initial"=>$this->opt['def_chk_adminsonly']);
    	}

		$f[] = array("type"=>"checkbox",
		             "name"=>"deleted",
					 "label"=>$lang_str['ff_show_deleted_users'],
					 "initial"=>false);

		return $f;
	}

	function action_enable(&$errors){
		global $data;
		
		$opt = array("uid"    => $this->controler->user_id->get_uid(),
		             "disable" => false);

		if (!$data->enable_user($opt)) return false;

		return array("m_sc_user_enabled=".RawURLEncode($this->opt['instance_id']));
	}


	function action_disable(&$errors){
		global $data;
		
		$opt = array("uid"    => $this->controler->user_id->get_uid(),
		             "disable" => true);
	
		if (!$data->enable_user($opt)) return false;

		return array("m_sc_user_disabled=".RawURLEncode($this->opt['instance_id']));
	}


	function action_delete(&$errors){
		global $data;
	
		if (!$data->mark_user_deleted(array("uid"=>$this->controler->user_id->get_uid()))) return false;

		return array("m_sc_user_deleted=".RawURLEncode($this->opt['instance_id']));
	}

	function action_self_delete(&$errors){
		global $data, $sess;
	
		if (!$data->mark_user_deleted(array("uid"=>$_SESSION['auth']->serweb_auth->get_uid()))) return false;
        $_SESSION['auth']->logout();
        $sess->delete();

        if ($this->opt['smarty_url_self_delete']) {
            $this->controler->change_url_for_reload($this->opt['smarty_url_self_delete']);
        }
		return array("m_sc_user_self_deleted=".RawURLEncode($this->opt['instance_id']));
	}

	function action_undelete(&$errors){
		global $data;
	    
	    $opt = array("uid"      => $this->controler->user_id->get_uid(),
                     "undelete" => true);
		if (!$data->mark_user_deleted($opt)) return false;

		return array("m_sc_user_undeleted=".RawURLEncode($this->opt['instance_id']));
	}

	function action_purge(&$errors){
		global $data;
	
		if (!$data->delete_sip_user($this->controler->user_id->get_uid())) return false;

		return array("m_sc_user_purged=".RawURLEncode($this->opt['instance_id']));
	}

	function action_default(&$errors){
		global $data, $sess;

        // Do nothing if $this->opt['get_user_list'] is false
		if (!$this->opt['get_user_list']) return true;
        
		$opt = array('get_user_aliases' => $this->opt['get_user_aliases'],
		             'get_sip_uri'      => $this->opt['get_user_sip_uri'],
					 'get_timezones'    => $this->opt['get_timezones'],
					 'only_agreeing'	=> $this->opt['get_only_agreeing'],
					 'get_disabled'		=> $this->opt['get_disabled'],
					 'get_credentials'	=> $this->opt['get_credentials']);

		if ($this->opt['only_from_administrated_domains']){
			if (false === $domains_perm = $_SESSION['auth']->get_administrated_domains()) return false;
			$opt['from_domains'] = $domains_perm;
		}
		
		if (is_a($this->sorter, "apu_base_class")){
			$opt['order_by']   = $this->sorter->get_sort_col();
			$opt['order_desc'] = $this->sorter->get_sort_dir();
		}

        $filter = array();
		if (is_a($this->filter, "apu_base_class")){
			$filter = $this->filter->get_filter();

    		$data->set_act_row($this->filter->get_act_row());
    		if ($this->opt['perm_display_deleted'] and 
                $filter['deleted']->value) {
                
                $opt['get_deleted'] = true;
            }
		}
		
		if (false === $this->subscribers = 
				$data->get_users(
					$filter, 
					$opt, 
					$errors)) 
			return false;

		$this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
		$this->pager['pos']=$data->get_act_row();
		$this->pager['items']=$data->get_num_rows();
		$this->pager['limit']=$data->get_showed_rows();
		$this->pager['from']=$data->get_res_from();
		$this->pager['to']=$data->get_res_to();

		foreach($this->subscribers as $key=>$val){
			$this->subscribers[$key]['url_dele'] = 
				$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
						"&sc_dele=".RawURLEncode($this->opt['instance_id']).
						"&".$val['get_param']);

			$this->subscribers[$key]['url_undele'] = 
				$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
						"&sc_undele=".RawURLEncode($this->opt['instance_id']).
						"&".$val['get_param']);

			$this->subscribers[$key]['url_purge'] = 
				$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
						"&sc_purge=".RawURLEncode($this->opt['instance_id']).
						"&".$val['get_param']);

			$this->subscribers[$key]['url_enable'] = 
				$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
						"&sc_enable=".RawURLEncode($this->opt['instance_id']).
						"&".$val['get_param']);

			$this->subscribers[$key]['url_disable'] = 
				$sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
						"&sc_disable=".RawURLEncode($this->opt['instance_id']).
						"&".$val['get_param']);

			if ($this->opt['script_phonebook']){
				$this->subscribers[$key]['url_add_to_pb'] = 
					$sess->url($this->opt['script_phonebook']."?kvrk=".uniqID("").
							"&add_from_wp=1".
							"&fname=".RawURLEncode($val['fname']).
							"&lname=".RawURLEncode($val['lname']).
							"&sip_uri=".RawURLEncode($val['sip_uri']));
			}
		}

		return true;
	}

	function form_invalid(){
		/* if deletion failed, get list of subscribers */
		if ($this->action['action'] == "delete" or
            $this->action['action'] == "disable"){
			$this->action_default($errors);
		}
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){

		if (isset($_GET['sc_self_dele']) and $_GET['sc_self_dele'] == $this->opt['instance_id']){
			$this->action=array('action'=>"self_delete",
			                    'validate_form'=>false,
								'reload'=>true);
			return;
		}

		if ($this->opt['allow_edit']){
	
			if (isset($_GET['sc_dele']) and $_GET['sc_dele'] == $this->opt['instance_id']){
				$this->action=array('action'=>"delete",
				                    'validate_form'=>true,
									'reload'=>true);
				return;
			}
	
			if ($this->opt['perm_undelete'] and isset($_GET['sc_undele']) and 
                $_GET['sc_undele'] == $this->opt['instance_id']){
				
                $this->action=array('action'=>"undelete",
				                    'validate_form'=>false,
									'reload'=>true);
				return;
			}
	
			if ($this->opt['perm_purge'] and isset($_GET['sc_purge']) and 
                $_GET['sc_purge'] == $this->opt['instance_id']){
                
				$this->action=array('action'=>"purge",
				                    'validate_form'=>false,
									'reload'=>true);
				return;
			}
	
			if (isset($_GET['sc_enable']) and $_GET['sc_enable'] == $this->opt['instance_id']){
				$this->action=array('action'=>"enable",
				                    'validate_form'=>false,
									'reload'=>true);
				return;
			}
	
			if (isset($_GET['sc_disable']) and $_GET['sc_disable'] == $this->opt['instance_id']){
				$this->action=array('action'=>"disable",
				                    'validate_form'=>true,
									'reload'=>true);
				return;
			}
		}		

		$this->action=array('action'=>"default",
		                     'validate_form'=>false,
							 'reload'=>false);
	}

    /**
     *	validate html form 
     *
     *	@param array $errors	array with error messages
     *	@return bool			TRUE if given values of form are OK, FALSE otherwise
     */
    function validate_form(&$errors){
        global $lang_str, $data, $config;
        $form_ok = true;
        
        if ($this->action['action'] == "delete"){
            if ($this->controler->user_id->get_uid() == 
                $_SESSION['auth']->serweb_auth->get_uid()){
                
                $errors[] = $lang_str['err_cannot_delete_own_account'];
                return false;
            }
        }
        elseif ($this->action['action'] == "disable"){
            if ($this->controler->user_id->get_uid() == 
                $_SESSION['auth']->serweb_auth->get_uid()){
                
                $errors[] = $lang_str['err_cannot_disable_own_account'];
                return false;
            }
        }
        
//      if (false === parent::validate_form($errors)) $form_ok = false;
        
        return $form_ok;
    }
    	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_sc_user_deleted']) and $_GET['m_sc_user_deleted'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_delete'];
			$this->smarty_action="was_deleted";
		}

		if (isset($_GET['m_sc_user_undeleted']) and $_GET['m_sc_user_undeleted'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_undelete'];
			$this->smarty_action="was_undeleted";
		}

		if (isset($_GET['m_sc_user_purged']) and $_GET['m_sc_user_purged'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_purge'];
			$this->smarty_action="was_purged";
		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty, $sess;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
		$smarty->assign_by_ref($this->opt['smarty_subscribers'], $this->subscribers);

		$smarty->assign_by_ref($this->opt['smarty_url_self_delete'], 
                            $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("").
						               "&sc_self_dele=".RawURLEncode($this->opt['instance_id'])));

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

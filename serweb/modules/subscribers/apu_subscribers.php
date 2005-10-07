<?php
/*
 * Application unit subscribers
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_subscribers.php,v 1.2 2005/10/07 14:01:14 kozlik Exp $
 * @package   serweb
 */ 

/* Application unit subscribers 
 *
 *
 *	This application unit is used for get list of subscribers and looking for subscribers
 *	   
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
 *	'only_from_same_domain'		(bool) default: false
 *	 set to true for display only users from same domain as admin
 *	 DEPRECATED
 *	
 *	'only_from_administrated_domains'	(bool) default: false
 *	 set to true for display only users from domains administrated by admin
 *	
 *	'get_user_aliases'			(bool) default: false
 *	 set to true if you want display aliases of users
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
 *	 
 */

class apu_subscribers extends apu_base_class{
	var $smarty_action='default';
	var $dele_user = null;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('get_users', 'delete_sip_user', 'check_admin_perms_to_user');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
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

		$this->opt['sess_seed'] =	0;

		$this->opt['only_from_same_domain'] = false;
		$this->opt['only_from_administrated_domains'] = false;

		$this->opt['get_user_aliases'] = false;

		/* message on attributes update */
		$this->opt['msg_delete']['short'] =	&$lang_str['msg_user_deleted_s'];
		$this->opt['msg_delete']['long']  =	&$lang_str['msg_user_deleted_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* pager */
		$this->opt['smarty_pager'] =		'pager';

		$this->opt['smarty_subscribers'] = 	'users';

		/* name of html form */
		$this->opt['form_name'] =			'';
		
		$this->opt['form_submit']=array('type' => 'image',
										'text' => $lang_str['b_find'],
										'src'  => get_path_to_buttons("btn_find.gif", $sess_lang));
		
	}

	function action_delete(&$errors){
		global $data, $lang_str, $serweb_auth;
	
		if ($this->opt['only_from_same_domain'] or $this->opt['only_from_administrated_domains']){
			if (0 > ($pp=$data->check_admin_perms_to_user($serweb_auth, $this->dele_user, $errors))) return false;
			
			if (!$pp){
				$errors[]=$lang_str['err_admin_can_not_delete_user_1']." '".$this->dele_user->uname."' ".$lang_str['err_admin_can_not_delete_user_2'];
				return false;
			}
		}

		if (!$data->delete_sip_user($this->dele_user, $errors)) return false;

		return array("m_sc_user_deleted=".RawURLEncode($this->opt['instance_id']));

//        Header("Location: ".$sess->url("users.php?kvrk=".uniqID("")."&m_user_deleted=".RawURLEncode($usr->uname."@".$usr->domain)));
	}

	function action_default(&$errors){
		global $data, $sess, $sess_apu_sc, $serweb_auth;

		$data->set_act_row($sess_apu_sc[$this->opt['sess_seed']]['act_row']);

		if (isset($serweb_auth->domains_perm) and is_array($serweb_auth->domains_perm)) $domains_perm = $serweb_auth->domains_perm;
		else $domains_perm = array();
		
		$opt = array('only_domain' => $this->opt['only_from_same_domain']?
							$serweb_auth->domain:
							null,
					 'from_domains' => $this->opt['only_from_administrated_domains']?
					 		$domains_perm:
					 		null,
					 'get_user_aliases' => $this->opt['get_user_aliases']);
		
		
		if (false === $this->subscribers = 
				$data->get_users(
					$sess_apu_sc[$this->opt['sess_seed']]['filter'], 
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
						"&".$val['get_param_d']);
		}

		return true;
	}

	
	/* this metod is called always at begining */
	function init(){
		global $sess, $sess_apu_sc;
		parent::init();

		/* registger session variable if still isn't registered */
		if (!$sess->is_registered('sess_apu_sc')) $sess->register('sess_apu_sc');
		
		/* set default value for session variable */
		if (!isset($sess_apu_sc[$this->opt['sess_seed']])){ 
			$tmp = array();
			$tmp['filter']['usrnm'] = '';
			$tmp['filter']['fname'] = '';
			$tmp['filter']['lname'] = '';
			$tmp['filter']['email'] = '';
			$tmp['filter']['domain'] = '';
			$tmp['filter']['onlineonly'] = $this->opt['def_chk_onlineonly'];
			$tmp['filter']['adminsonly'] = $this->opt['def_chk_adminsonly'];

			$tmp['act_row'] = 0;

			$sess_apu_sc[$this->opt['sess_seed']] = &$tmp;
		}

		if (isset($_GET['act_row'])) 
			$sess_apu_sc[$this->opt['sess_seed']]['act_row'] = $_GET['act_row'];

	}

	function set_filter_by_posts(){
		global $sess_apu_sc;

		/* show results from first row after form submit */
		$sess_apu_sc[$this->opt['sess_seed']]['act_row'] = 0;
		
		/* set search filter by values submited by form */
		$filter = &$sess_apu_sc[$this->opt['sess_seed']]['filter'];
	
		if (isset($_POST['usrnm'])) $filter['usrnm']=$_POST['usrnm'];
		if (isset($_POST['fname'])) $filter['fname']=$_POST['fname'];
		if (isset($_POST['lname'])) $filter['lname']=$_POST['lname'];
		if (isset($_POST['email'])) $filter['email']=$_POST['email'];
		if (isset($_POST['domain'])) $filter['domain']=$_POST['domain'];

		if ($this->opt['use_chk_onlineonly']){
			if (isset($_POST['onlineonly'])) 
				$filter['onlineonly']=$_POST['onlineonly'];
			else $filter['onlineonly']=0;
		}

		if ($this->opt['use_chk_adminsonly']){
			if (isset($_POST['adminsonly'])) 
				$filter['adminsonly']=$_POST['adminsonly'];
			else $filter['adminsonly']=0;
		}

		$this->f->load_defaults();
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){

		if (isset($_GET['sc_dele']) and $_GET['sc_dele'] == $this->opt['instance_id']){
			if (false !== $this->dele_user = get_userauth_from_get_param('d')){
				$this->action=array('action'=>"delete",
				                    'validate_form'=>false,
									'reload'=>true);
				return;
			}
			sw_log("user should be deleted, but can't get identificator from GET param", PEAR_LOG_DEBUG);
		}
		

		if ($this->was_form_submited()){	// Is there data to process?
			$this->action=array('action'=>"default",
			                    'validate_form'=>true,
								'reload'=>false);
		}
		else $this->action=array('action'=>"default",
			                     'validate_form'=>false,
								 'reload'=>false);
	}
	
	/* create html form */
	function create_html_form(&$errors){
		global $sess_apu_sc;
		parent::create_html_form($errors);

		$filter = &$sess_apu_sc[$this->opt['sess_seed']]['filter'];

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"usrnm",
									 "size"=>11,
									 "maxlength"=>50,
		                             "value"=>$filter['usrnm']));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"fname",
									 "size"=>11,
									 "maxlength"=>25,
		                             "value"=>$filter['fname']));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"lname",
									 "size"=>11,
									 "maxlength"=>45,
		                             "value"=>$filter['lname']));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"email",
									 "size"=>11,
									 "maxlength"=>50,
		                             "value"=>$filter['email']));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"domain",
									 "size"=>11,
									 "maxlength"=>128,
	        	                     "value"=>$filter['domain']));

		$this->f->add_element(array("type"=>"checkbox",
		                             "value"=>1,
									 "checked"=>$filter['onlineonly'],
	    	                         "name"=>"onlineonly"));

		$this->f->add_element(array("type"=>"checkbox",
		                             "value"=>1,
									 "checked"=>$filter['adminsonly'],
	    	                         "name"=>"adminsonly"));


	}

	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;

		$this->set_filter_by_posts();
		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_sc_user_deleted']) and $_GET['m_sc_user_deleted'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_delete'];
			$this->smarty_action="was_deleted";
		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);
		$smarty->assign_by_ref($this->opt['smarty_subscribers'], $this->subscribers);
	}
	
	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => '',
					 'before'      => '');
	}
}


?>

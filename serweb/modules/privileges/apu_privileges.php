<?php
/**
 * Application unit privileges 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_privileges.php,v 1.6 2005/12/22 13:26:28 kozlik Exp $
 * @package   serweb
 */ 

/** Application unit privileges 
 *
 *
 *	This application unit is used for changing privileges of user
 *	   
 *	Configuration:
 *	--------------
 *
 *	'disabled_privileges'		(array) default: array()
 *	 list of privileges that can't be changed
 *	
 *	'redirect_on_update'		(string) default: ''
 *	 name of script to which is browser redirected after succesfull update
 *	 if empty, browser isn't redirected
 *	
 *	'msg_update'				default: $lang_str['msg_privileges_updated_s'] and $lang_str['msg_privileges_updated_l']
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
 *	'smarty_groups'				name of smarty variable - see below
 *	'smarty_enabled_privileges'	name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_action']			(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'was_updated' - when user submited form and data was succefully stored
 *	
 *	opt['smarty_groups']		(grp)
 *	 contain all possible values in ACL
 *	
 *	opt['smarty_enabled_privileges']	(en_priv)
 *	 associative array - keys are privilege names, values are 0 and 1 (enabled/disabled)
 *
 *	opt['smarty_allow_change']	(allow_change_privileges)
 *	 flag which says if privileges can be changed
 */

class apu_privileges extends apu_base_class{
	var $smarty_action='default';
	var $privileges;
	var $enabled_privileges;
	/** flag for smarty template - is unset when changeing privileges isn't allowed */
	var $allow_change_priv = true;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array();
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_privileges(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['disabled_privileges'] = array();
		$this->opt['redirect_on_update'] = "";

		/* message on attributes update */
		$this->opt['msg_update']['short'] =	&$lang_str['msg_privileges_updated_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_privileges_updated_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'f_priv';

		$this->opt['smarty_groups'] = 		'grp';		

		$this->opt['smarty_enabled_privileges'] = 		'en_priv';		
		
		$this->opt['smarty_allow_change'] =	'allow_change_privileges';
	}

	/*
		update privilege in DB
		$priv_name - name of privilege
		$priv_type - associative array
			$priv_type['type'] - type of privilege can be 'boolean' or 'multivalue'
			$priv_type['values'] - for 'multivalue' type used to store array of potential values
		$errors - array in which arrors messages are returned
	*/
	
	/* this metod is called always at begining */
	function init(){
		parent::init();

		$this->privileges = array();
		$this->privileges['acl_control'] = array();
		$this->privileges['is_admin'] = false;
		$this->privileges['hostmaster'] = false;

		$this->enabled_privileges = array('is_admin' => 1,
										  'hostmaster' => 1,
										  'acl_control' => 1 );

		foreach ($this->opt['disabled_privileges'] as $v){
			$this->enabled_privileges[$v] = 0;
		}
	}
	
	function update_db($priv_name, $attr_name, $values){
		global $config;

		/* get user attrs object */
		$ua = &User_Attrs::singleton($this->user_id->get_uid());

		/* get type of attribute */
		$at = &Attr_Types::singleton();
		if (false === $type = $at->get_attr_type($attr_name)) return false;
	
		if ($type->is_multivalue()){
			$priv_values = array();
			foreach ($values as $row){
				//if checkbox is checked, assign value to $priv_vals
				if (!empty($_POST["pr_chk_".$row])) $priv_values[] = $row;
			}

			if (false === $ua->set_attribute($attr_name, $priv_values)) return false;
		
		}
		else{
			if (!empty($_POST["pr_chk_".$priv_name])){
				if (false === $ua->set_attribute($attr_name, "1")) return false;
			}
			else{
				if (false === $ua->unset_attribute($attr_name)) return false;
			}
		}
	
		return true;
	} //end function update_db


	function action_update(&$errors){
		global $config;
		
		$an = &$config->attr_names;
		
		if ($this->enabled_privileges['is_admin']){
			if (false === $this->update_db('is_admin', $an['is_admin'], null)) return false;
		}
		if ($this->enabled_privileges['hostmaster']){
			if (false === $this->update_db('hostmaster', $an['is_hostmaster'], null)) return false;
		}
		if ($this->enabled_privileges['acl_control']){
			if (false === $this->update_db('acl_control', $an['acl_control'], $config->grp_values)) return false;
		}

		if ($this->opt['redirect_on_update'])
			$this->controler->change_url_for_reload($this->opt['redirect_on_update']);

		return array("m_pr_updated=".RawURLEncode($this->opt['instance_id']));
	}

	function action_set_admin_privileges(&$errors){
		global $config;

		$an = &$config->attr_names;

		/* get user attrs object */
		$ua = &User_Attrs::singleton($this->user_id->get_uid());
		if (false === $ua->set_attribute($an['is_admin'], "1")) return false;

	
		return array("m_pr_updated=".RawURLEncode($this->opt['instance_id']));
	}
	
	function action_default(&$errors){
	}

	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if (isset($_GET['pr_set_admin_privilege'])){
			$this->action=array('action'=>"set_admin_privileges",
			                    'validate_form'=>false,
								'reload'=>true);
			return;
		}

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
		global $config;
		parent::create_html_form($errors);

		$an = &$config->attr_names;

		$ua = &User_Attrs::singleton($this->user_id->get_uid());
		if (false === $user_attrs = &$ua->get_attributes()) return false;

		$this->privileges['is_admin']    = isset($user_attrs[$an['is_admin']]) ? $user_attrs[$an['is_admin']] : false;
		$this->privileges['hostmaster']  = isset($user_attrs[$an['is_hostmaster']]) ? $user_attrs[$an['is_hostmaster']] : false;
		$this->privileges['acl_control'] = isset($user_attrs[$an['acl_control']]) ? $user_attrs[$an['acl_control']] :array();
	
		/* add form elements */
		foreach ($config->grp_values as $row){
			$this->f->add_element(array("type"=>"checkbox",
			                      "name"=>"pr_chk_".$row,
			                      "checked"=>in_array($row, $this->privileges['acl_control'])?"1":"0",
			                      "value"=>"1"));
		}
	
		$this->f->add_element(array("type"=>"checkbox",
		                      "name"=>"pr_chk_hostmaster",
		                      "checked"=>isset($this->privileges['hostmaster'][0]) and $this->privileges['hostmaster'][0]?"1":"0",
		                      "value"=>"1"));

	
		$this->f->add_element(array("type"=>"checkbox",
		                      "name"=>"pr_chk_is_admin",
		                      "checked"=>isset($this->privileges['is_admin'][0]) and $this->privileges['is_admin'][0]?"1":"0",
		                      "value"=>"1",
							  "extrahtml"=>"onclick='disable_chk(this);'"));
	

		$js = "
			/* disable other checkboxes if is_admin checkbox is not checked */
		
			function disable_chk(is_admin){
				f=is_admin.form;

				dis = !is_admin.checked;

				if (f.pr_chk_hostmaster)        f.pr_chk_hostmaster.disabled = dis;
		";

		foreach ($config->grp_values as $row)
			$js .= "
				if (f.pr_chk_".$row.") f.pr_chk_".$row.".disabled = dis;";


		$js.="
			}

			/* disable other checkboxes if is_admin checkbox is not checked */
			disable_chk(document.".$this->opt['form_name'].".pr_chk_is_admin);

		";

		$this->controler->set_onload_js($js);


	}

	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;
		return true;
	}
	
	
	/* add messages to given array */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_pr_updated']) and $_GET['m_pr_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty, $config;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_enabled_privileges'], $this->enabled_privileges);
		$smarty->assign($this->opt['smarty_groups'], $config->grp_values);
		$smarty->assign($this->opt['smarty_allow_change'], $this->allow_change_priv);
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

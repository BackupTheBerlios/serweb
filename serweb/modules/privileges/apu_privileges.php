<?php
/**
 * Application unit privileges 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_privileges.php,v 1.2 2005/09/22 14:11:43 kozlik Exp $
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
 */

class apu_privileges extends apu_base_class{
	var $smarty_action='default';
	var $privileges;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('add_privilege_to_user', 'del_privilege_of_user', 'get_privileges_of_user');
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
		
	}

	/*
		update privilege in DB
		$priv_name - name of privilege
		$priv_type - associative array
			$priv_type['type'] - type of privilege can be 'boolean' or 'multivalue'
			$priv_type['values'] - for 'multivalue' type used to store array of potential values
		$errors - array in which arrors messages are returned
	*/
	
	function update_db($priv_name, $priv_type, &$errors){
		global $config, $data;
	
		switch ($priv_type['type']){
		case "boolean":
			//if checkbox isn't checked, assign value "0" to variable
			if (!isset($_POST["pr_chk_".$priv_name])) $_POST["pr_chk_".$priv_name] = "0";
	
			if ($_POST["pr_chk_".$priv_name] != $_POST["pr_hidden_".$priv_name]){
				if ($_POST["pr_chk_".$priv_name]){
					if (false === $data->add_privilege_to_user($this->user_id, $priv_name, '1', isset($ad_priv[$priv_name][0]), $errors)) return false;
				}
				else{
					if (false === $data->del_privilege_of_user($this->user_id, $priv_name, NULL, $errors)) return false;
				}
			}
			break;
	
		case "multivalue":
			foreach ($priv_type['values'] as $row){
				//if checkbox isn't checked, assign value "0" to variable
				if (!isset($_POST["pr_chk_".$row])) $_POST["pr_chk_".$row] = "0";
	
				//if state of checkbox was changed
				if ($_POST["pr_chk_".$row] != $_POST["pr_hidden_".$row]){
					if ($_POST["pr_chk_".$row]){
						if (false === $data->add_privilege_to_user($this->user_id, $priv_name, $row, false, $errors)) return false;
					}
					else{
						if (false === $data->del_privilege_of_user($this->user_id, $priv_name, $row, $errors)) return false;
					}
				}
			}
			break;
		default:
			$errors[]="non existent priv type"; return false;
		}//end switch
	
		return true;
	} //end function update_db


	function action_update(&$errors){
		global $config;
		
		if (false === $this->update_db('is_admin', array('type'=>'boolean'), $errors)) return false;
		if (false === $this->update_db('change_privileges', array('type'=>'boolean'), $errors)) return false;
		if (false === $this->update_db('hostmaster', array('type'=>'boolean'), $errors)) return false;
		if (false === $this->update_db('acl_control', array('type'=>'multivalue', 'values'=>$config->grp_values), $errors)) return false;

		if ($this->opt['redirect_on_update'])
			$this->controler->change_url_for_reload($this->opt['redirect_on_update']);

		return array("m_pr_updated=".RawURLEncode($this->opt['instance_id']));
	}
	
	/* this metod is called always at begining */
	function init(){
		parent::init();

		$this->privileges = array();
		$this->privileges['acl_control'] = array();
		$this->privileges['change_privileges'] = array();
		$this->privileges['is_admin'] = array();
		$this->privileges['hostmaster'] = array();
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
		global $data, $config;
		parent::create_html_form($errors);

		/* get privileges of user */
		if (false === $privs = $data->get_privileges_of_user($this->user_id, NULL, $errors)) return false;
		foreach($privs as $row)	
			$this->privileges[$row->priv_name][]=$row->priv_value;
	
		/* add form elements */
		foreach ($config->grp_values as $row){
			$this->f->add_element(array("type"=>"checkbox",
			                      "name"=>"pr_chk_".$row,
			                      "checked"=>in_array($row, $this->privileges['acl_control'])?"1":"0",
			                      "value"=>"1"));
	
			$this->f->add_element(array("type"=>"hidden",
			                      "name"=>"pr_hidden_".$row,
			                      "value"=>in_array($row, $this->privileges['acl_control'])?"1":"0"));
		}
	
		$this->f->add_element(array("type"=>"checkbox",
		                      "name"=>"pr_chk_change_privileges",
		                      "checked"=>isset($this->privileges['change_privileges'][0]) and $this->privileges['change_privileges'][0]?"1":"0",
		                      "value"=>"1"));
	
		$this->f->add_element(array("type"=>"hidden",
		                      "name"=>"pr_hidden_change_privileges",
		                      "value"=>isset($this->privileges['change_privileges'][0]) and $this->privileges['change_privileges'][0]?"1":"0"));


		$this->f->add_element(array("type"=>"checkbox",
		                      "name"=>"pr_chk_hostmaster",
		                      "checked"=>isset($this->privileges['hostmaster'][0]) and $this->privileges['hostmaster'][0]?"1":"0",
		                      "value"=>"1"));
	
		$this->f->add_element(array("type"=>"hidden",
		                      "name"=>"pr_hidden_hostmaster",
		                      "value"=>isset($this->privileges['hostmaster'][0]) and $this->privileges['hostmaster'][0]?"1":"0"));

	
		$this->f->add_element(array("type"=>"checkbox",
		                      "name"=>"pr_chk_is_admin",
		                      "checked"=>isset($this->privileges['is_admin'][0]) and $this->privileges['is_admin'][0]?"1":"0",
		                      "value"=>"1",
							  "extrahtml"=>"onclick='disable_chk(this);'"));
	
		$this->f->add_element(array("type"=>"hidden",
		                      "name"=>"pr_hidden_is_admin",
		                      "value"=>isset($this->privileges['is_admin'][0]) and $this->privileges['is_admin'][0]?"1":"0"));

		$js = "
			/* disable other checkboxes if is_admin checkbox is not checked */
		
			function disable_chk(is_admin){
				f=is_admin.form;

				dis = !is_admin.checked;

				if (f.pr_chk_change_privileges) f.pr_chk_change_privileges.disabled = dis;
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
		$smarty->assign($this->opt['smarty_groups'], $config->grp_values);
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

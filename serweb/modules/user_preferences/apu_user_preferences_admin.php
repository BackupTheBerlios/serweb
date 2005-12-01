<?php
/*
 * Application unit apu_user_preferences_admin
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_user_preferences_admin.php,v 1.4 2005/12/01 16:11:29 kozlik Exp $
 * @package   serweb
 */ 

/* Application unit apu_user_preferences_admin 
 *
 *
 *	This application unit is used for change user preferences (types, names, default values, etc.)
 *	   
 *	Configuration:
 *	--------------
 *	
 *	'edit_list_items_script'	(string) default: 'edit_list_items.php'
 *	 url of script for edit set of possible values of attribute type
 *	 list or radio
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
 *	'smarty_list_items_link'	name of smarty variable - see below
 *	'smarty_attributes'			name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	
 *	opt['smarty_list_items_link']		('url_edit_list')
 *	 contain url of script for edit set of possible values of attribute type
 *	 list or radio
 *	
 *	opt['smarty_attributes']			('attributes')
 *	
 *	
 *	
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_action']			(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'edit' - 
 *	
 */

class apu_user_preferences_admin extends apu_base_class{
	var $smarty_action='default';
	var $att_id = null;
	var $attribute = null;
	var $attributes = null;

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('del_attribute', 'get_attribute', 'update_attribute', 'get_attr_types');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_user_preferences_admin(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['edit_list_items_script'] = "edit_list_items.php";

		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';

		$this->opt['smarty_list_items_link'] = 'url_edit_list';
		$this->opt['smarty_attributes'] =	'attributes';

		/* name of html form */
		$this->opt['form_name'] =			'';
		
		
	}

	function get_attributes(&$errors){
		global $sess, $data;
		
		$this->attributes = array();
		
		if (false === $attributes = $data->get_attr_types($this->att_id, $errors)) return false;

		$i=0;
		foreach($attributes as $att){
			$this->attributes[$i]['att_name']  = $att->att_name;
			$this->attributes[$i]['att_type']  = $this->usr_pref->att_types[$att->att_rich_type]->label;
			$this->attributes[$i]['def_value'] = $this->usr_pref->format_value_for_output($att->default_value, $att->att_rich_type, $att->att_type_spec);
			$this->attributes[$i]['url_dele']  = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&up_dele_id=".RawURLEncode($att->att_name));
			$this->attributes[$i]['url_edit']  = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&up_edit_id=".RawURLEncode($att->att_name));
			$i++;
		}

		unset($attributes);
		return true;
	}


	function action_update(&$errors){
		global $data;

		if (false === $data->update_attribute($this->att_id, 
		                                      $_POST['up_att_name'], 
											  $_POST['up_att_rich_type'], 
											  $this->usr_pref->att_types[$_POST['up_att_rich_type']]->raw_type, 
											  $_POST['up_default_value'], 
											  $errors)) {

			$this->get_attributes($errors);
			return false;
		}

		return true;
	}

	function action_add(&$errors){
		global $data;

		if (false === $data->update_attribute(NULL, 
		                                      $_POST['up_att_name'], 
											  $_POST['up_att_rich_type'], 
											  $this->usr_pref->att_types[$_POST['up_att_rich_type']]->raw_type, 
											  $_POST['up_default_value'], 
											  $errors)) {
			$this->get_attributes($errors);
			return false;
		}

		if ($_POST['up_att_rich_type']=="list" or $_POST['up_att_rich_type']=="radio"){
			$this->controler->change_url_for_reload(
				$this->opt['edit_list_items_script']."?attrib_name=".RawURLEncode($_POST['up_att_name'])."&kvrk=".uniqID(""));
		}

		return true;
	}


	function action_delete(&$errors){
		global $data;

		if (false === $data->del_attribute($this->att_id, $errors)) return false;
		return true;
	}


	function action_edit(&$errors){
		if (false === $this->get_attributes($errors)) return false;
		$this->smarty_action="edit";
	}

	function action_default(&$errors){
		if (false === $this->get_attributes($errors)) return false;
	}
	
	/* this metod is called always at begining */
	function init(){
		global $_SERWEB;
		parent::init();

		$this->usr_pref = new User_Preferences();
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			if ($_POST['up_att_id']){
				$this->att_id = $_POST['up_att_id'];

				$this->action = array('action'=>"update",
			                          'validate_form'=>true,
				                      'reload'=>true);
			}
			else {
				$this->action = array('action'=>"add",
			                          'validate_form'=>true,
				                      'reload'=>true);
			}
			return;
		}
		
		if (isset($_GET['up_edit_id'])){
			$this->att_id = $_GET['up_edit_id'];

			$this->action = array('action'=>"edit",
			                      'validate_form'=>false,
			                      'reload'=>false);
			return;
		}

		if (isset($_GET['up_dele_id'])){
			$this->att_id = $_GET['up_dele_id'];

			$this->action = array('action'=>"delete",
			                      'validate_form'=>false,
			                      'reload'=>true);
			return;
		}

		$this->action=array('action'=>"default",
		                     'validate_form'=>false,
							 'reload'=>false);

	}
	
	/* create html form */
	function create_html_form(&$errors){
		global $lang_str, $data;
		parent::create_html_form($errors);

		if ($this->action['action'] == 'edit'){
			if (false === $this->attribute = $data->get_attribute($this->att_id, $errors)) return false;
		}

		//create array of options of select
		$opt=array();
		foreach($this->usr_pref->att_types as $k => $v){
			$opt[]=array("label" => $v->label, "value" => $k);
		}
	
	
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"up_att_name",
		                             "value"=>isset($this->attribute->att_name) ? $this->attribute->att_name : "",
									 "size"=>16,
									 "maxlength"=>32,
									 "minlength"=>1,
	//	                             "valid_regex"=>"^[a-zA-Z_][a-zA-Z0-9_]*$",
	//	                             "valid_e"=>"in attribut name use only charakters 'A-Z', '0-9' and '_'",
									 "length_e"=>$lang_str['fe_not_filled_name_of_attribute']));
	
		$this->f->add_element(array("type"=>"select",
	    	                         "name"=>"up_att_rich_type",
		                             "value"=>isset($this->attribute->att_rich_type) ? $this->attribute->att_rich_type : "",
									 "size"=>1,
									 "options"=>$opt));
	
		$this->f->add_element(array("type"=>"text",
	    	                         "name"=>"up_default_value",
		                             "value"=>isset($this->attribute->default_value) ? $this->attribute->default_value : "",
									 "size"=>16,
									 "maxlength"=>255));
	
		$this->f->add_element(array("type"=>"hidden",
		                             "name"=>"up_att_id",
		                             "value"=>$this->att_id));


	}

	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;

		//check and format default value of attribute
		if (!$this->usr_pref->format_inputed_value($_POST['up_default_value'], 
		                                           $_POST['up_att_rich_type'], 
												   $this->attribute->att_type_spec)){
			$errors[]="bad default value"; 
			$this->get_attributes($errors);
			return false;
		}

		return true;
	}
	
	
	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty, $sess;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign_by_ref($this->opt['smarty_attributes'], $this->attributes);

		if ($this->action['action'] == 'edit' and 
		   ($this->attribute->att_rich_type =="list" or 
		    $this->attribute->att_rich_type == "radio")){

			$smarty->assign(
				$this->opt['smarty_list_items_link'], 
				$sess->url($this->opt['edit_list_items_script']."?attrib_name=".RawURLEncode($this->att_id)."&kvrk=".uniqID(""))
			);
		}
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

<?php
/*
 * Application unit apu_user_preferences_li 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_user_preferences_li.php,v 1.1 2005/08/18 12:08:49 kozlik Exp $
 * @package   serweb
 */ 

/* Application unit apu_user_preferences_li 
 *
 *
 *	This application unit is used for edit set of possible values of attribute 
 *	type list or radio
 *	   
 *	Configuration:
 *	--------------
 *	
 *	'edit_up_script'			(string) default: "user_preferences.php"
 *	 name of script for edit user preferences
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
 *	'smarty_items'				name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	opt['smarty_action']			(action)
 *	  tells what should smarty display. Values:
 *	  'default' - 
 *	  'edit' - 
 *	
 *	opt['smarty_items']			(item_list)
 *	 array containing list of possible values of attribute
 */

class apu_user_preferences_li extends apu_base_class{
	var $smarty_action='default';
	var $item_id=null;
	
	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array('update_att_type_spec', 'get_attribute');
	}

	/* return array of strings - requred javascript files */
	function get_required_javascript(){
		return array();
	}
	
	/* constructor */
	function apu_user_preferences_li(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['edit_up_script'] = "user_preferences.php";
		
		/* message on attributes update */
		$this->opt['msg_update']['short'] =	&$lang_str['msg_changes_saved_s'];
		$this->opt['msg_update']['long']  =	&$lang_str['msg_changes_saved_l'];
		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* smarty action */
		$this->opt['smarty_action'] =		'action';
		/* name of html form */
		$this->opt['form_name'] =			'';

		$this->opt['smarty_items'] = 		'item_list';
		
	}

	function action_return_back(&$errors){
		$this->controler->change_url_for_reload(
				$this->opt['edit_up_script']."?kvrk=".uniqID(""));
		return true;
	}

	function action_update(&$errors){
		global $data, $sess_apu_sd_li;

		//find item in array and replace it by new item
		foreach($this->item_list as $key=>$row){
			if ($row->label == $this->item_id){
				$this->item_list[$key]=new UP_List_Items($_POST['up_item_label'], $_POST['up_item_value']);
				break;
			}
		}

		if (false === $data->update_att_type_spec(
					$sess_apu_sd_li['attrib_name'], 
					serialize($this->item_list), 
					(isset($_POST['up_set_default']) and $_POST['up_set_default'])?$_POST['up_item_value']:null,
					$errors)) 
			return false;

		return true;
	}

	function action_add(&$errors){
		global $data, $sess_apu_sd_li;

		$this->item_list[]=new UP_List_Items($_POST['up_item_label'], $_POST['up_item_value']);

		if (false === $data->update_att_type_spec(
					$sess_apu_sd_li['attrib_name'], 
					serialize($this->item_list), 
					(isset($_POST['up_set_default']) and $_POST['up_set_default'])?$_POST['up_item_value']:null,
					$errors)) 
			return false;

		return true;
	}

	function action_delete(&$errors){
		global $data, $sess_apu_sd_li;

		//find item in array and unset it
		foreach($this->item_list as $key=>$row){
			if ($row->label == $this->item_id){
				unset($this->item_list[$key]);
				break;
			}
		}

		if (false === $data->update_att_type_spec(
					$sess_apu_sd_li['attrib_name'], 
					serialize($this->item_list), 
					null,
					$errors)) 
			return false;

		return true;
	}

	function action_edit(&$errors){
		$this->smarty_action="edit";
	}

	
	/* this metod is called always at begining */
	function init(){
		global $_SERWEB, $sess_apu_sd_li, $sess;
		parent::init();

		require_once ($_SERWEB["serwebdir"] . "user_preferences.php");

		/* registger session variable if still isn't registered */
		if (!$sess->is_registered('sess_apu_sd_li')) $sess->register('sess_apu_sd_li');
		
		if (isset($_GET['attrib_name'])){
			$sess_apu_sd_li['attrib_name'] = $_GET['attrib_name'];
		}

	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		global $sess_apu_sd_li, $data;

		$act_back=array('action'=>"return_back",
	                    'validate_form'=>false,
						'reload'=>true,
						'alone'=>true);

		if (!$sess_apu_sd_li['attrib_name']){
			$this->action = $act_back;
			return;
		}

		//get attrib from DB
		if (false === $row = $data->get_attribute($sess_apu_sd_li['attrib_name'], $errors)) {
			$this->action = $act_back;
			return;
		}

		if ($row->att_rich_type!="list" and $row->att_rich_type!="radio"){
			//attrib isn't neither list of items nor radio -> nothing to edit -> go back to attributes editing page
			$this->action = $act_back;
			return;
		}

		$this->item_list = unserialize(is_string($row->att_type_spec) ? $row->att_type_spec : "");
		$this->default_value = $row->default_value;
		if(!$this->item_list) $this->item_list = array();



		if ($this->was_form_submited()){	// Is there data to process?
			if ($_POST['up_item_id']){
				$this->item_id = $_POST['up_item_id'];

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
		
		if (isset($_GET['up_edit_item'])){
			$this->item_id = $_GET['up_edit_item'];

			$this->action = array('action'=>"edit",
			                      'validate_form'=>false,
			                      'reload'=>false);
			return;
		}

		if (isset($_GET['up_dele_item'])){
			$this->item_id = $_GET['up_dele_item'];

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
		global $lang_str;
		parent::create_html_form($errors);

		//if user want edit item
		if ($this->action['action'] == 'edit'){
			//find value of item in order to we can fill it in the form
			foreach($this->item_list as $row){
				if ($row->label == $this->item_id){
					$it = $row;
					break;
				}
			}
		}


		$this->f->add_element(array("type"=>"text",
		                             "name"=>"up_item_label",
		                             "value"=>isset($it->label) ? $it->label : "",
									 "size"=>16,
									 "maxlength"=>255,
									 "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_item_label']));
	
		$this->f->add_element(array("type"=>"text",
	    	                         "name"=>"up_item_value",
		                             "value"=>isset($it->value) ? $it->value : "",
									 "size"=>16,
									 "maxlength"=>255,
									 "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_item_value']));
	
		$this->f->add_element(array("type"=>"checkbox",
	    	                         "name"=>"up_set_default",
		                             "value"=>"1",
									 "checked"=>(isset($it->value) and $it->value == $this->default_value)?1:0));
	
		$this->f->add_element(array("type"=>"hidden",
		                             "name"=>"up_item_id",
		                             "value"=>$this->item_id));

	}

	/* validate html form */
	function validate_form(&$errors){
		if (false === parent::validate_form($errors)) return false;
		return true;
	}
	
	
	function format_items_for_output(){
		global $sess;
	
		$out=array();
		$i=0;
		foreach($this->item_list as $item){
			if ($item->label == $this->item_id) continue;
	
			$out[$i]['label'] = $item->label;
			$out[$i]['value'] = $item->value;
			$out[$i]['url_dele'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&up_dele_item=".RawURLEncode($item->label));
			$out[$i]['url_edit'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&up_edit_item=".RawURLEncode($item->label));
			$i++;
		}
	
		return $out;
	}


	/* assign variables to smarty */
	function pass_values_to_html(){
		global $smarty;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);
		$smarty->assign($this->opt['smarty_items'], $this->format_items_for_output());
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

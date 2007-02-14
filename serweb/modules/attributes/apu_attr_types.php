<?php
/**
 * Application unit attribute types
 * 
 * @author     Karel Kozlik
 * @version    $Id: apu_attr_types.php,v 1.10 2007/02/14 16:36:40 kozlik Exp $
 * @package    serweb
 * @subpackage mod_attributes
 */ 

/**
 *	Application unit attribute types
 *
 *
 *	This application unit is used for editing attribute types
 *	   
 *	<pre>
 *	Configuration:
 *	--------------
 *	'type_spec_script'			(string)	default: 'edit_type_spec.php'
 *	 script for editing 'type_spec' field of attribute.
 *								
 *	'form_submit_extended'		(array)
 *	 form submit element for access extendend settings. ('type_spec_script')
 *	 This is an assotiative array describing submit element of form. For details see description 
 *	 of method add_submit in class form_ext
 *								
 *	'msg_update'					default: $lang_str['msg_changes_saved_s'] and $lang_str['msg_changes_saved_l']
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
 *	'smarty_attrs'				name of smarty variable - see below
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
 *	opt['smarty_attrs']			(attrs)
 *	  array of all attributes
 *	
 *	opt['smarty_pager']				(pager)
 *	 associative array containing size of result and which page is returned
 *	
 *	</pre>
 *	@package    serweb
 *	@subpackage mod_attributes
 */

class apu_attr_types extends apu_base_class{
	var $smarty_action='default';
	/** Currently edited attribute */
	var $edit_id;
	/** List of attributes in form for Smarty */
	var $smarty_attrs;
	/** array of all attributes */
	var $attrs;
	/** should be user redirected to extended settings page? */
	var $ext_settings = false;
	/** List of groups of attributes */
	var $attr_groups;

	var $sorter=null;
	var $filter=null;

	/** 
	 *	return required data layer methods - static class 
	 *
	 *	@return array	array of required data layer methods
	 */
	function get_required_data_layer_methods(){
		return array('update_attr_type', 'del_attr_type');
	}

	/**
	 *	return array of strings - required javascript files 
	 *
	 *	@return array	array of required javascript files
	 */
	function get_required_javascript(){
		return array();
	}
	
	/**
	 *	constructor 
	 *	
	 *	initialize internal variables
	 */
	function apu_attr_types(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		

		$this->opt['type_spec_script'] =		'edit_type_spec.php';

		$this->opt['form_submit_extended']=array('type' => 'image',
		                                         'text' => $lang_str['b_extended_settings'],
		                                         'src'  => get_path_to_buttons("btn_extended_settings.gif", $_SESSION['lang']));


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
		/* pager */
		$this->opt['smarty_pager'] =		'pager';
		
		$this->opt['smarty_attrs'] =			'attrs';
		$this->opt['smarty_groups'] =			'groups';
		$this->opt['smarty_url_toggle_groups'] = 'url_toggle_groups';
		$this->opt['smarty_show_groups'] = 		'show_groups';
		
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
		parent::init();

		$session_name = empty($this->opt['filter_name'])?
		                $this->opt['instance_id']:
		                $this->opt['filter_name'];

		if (!isset($_SESSION['apu_filter'][$session_name])){
			$_SESSION['apu_filter'][$session_name] = array();
		}
		
		$this->session = &$_SESSION['apu_filter'][$session_name];

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
		return array('order', 'name', 'rich_type', 'desc', 'default_flags', 
		             'flags', 'priority', 'access', 'group', 
					 'priority_r', 'priority_u', 'priority_d', 'priority_g', 
					 'd_flags_s', 'd_flags_sw',
					 'flags_r', 'flags_m', 'flags_e');
	}
	
	function get_filter_form(){
		global $lang_str;
		
		$f = array();

		$f[] = array("type"=>"text",
		             "name"=>"order",
					 "label"=>$lang_str['ff_order']);

		$f[] = array("type"=>"text",
		             "name"=>"name",
					 "label"=>$lang_str['ff_att_name']);

		$f[] = array("type"=>"text",
		             "name"=>"rich_type",
					 "label"=>$lang_str['ff_att_type']);

		$f[] = array("type"=>"text",
		             "name"=>"desc",
					 "label"=>$lang_str['ff_label']);

		$f[] = array("type"=>"text",
		             "name"=>"group",
					 "label"=>$lang_str['ff_att_group']);

		$f[] = array("type"=>"checkbox",
		             "name"=>"priority_r",
					 "label"=>$lang_str['ff_att_uri'],
					 "initial"=>1,
					 "3state"=>true);

		$f[] = array("type"=>"checkbox",
		             "name"=>"priority_u",
					 "label"=>$lang_str['ff_att_user'],
					 "initial"=>1,
					 "3state"=>true);

		$f[] = array("type"=>"checkbox",
		             "name"=>"priority_d",
					 "label"=>$lang_str['ff_att_domain'],
					 "initial"=>1,
					 "3state"=>true);

		$f[] = array("type"=>"checkbox",
		             "name"=>"priority_g",
					 "label"=>$lang_str['ff_att_global'],
					 "initial"=>1,
					 "3state"=>true);

		$f[] = array("type"=>"checkbox",
		             "name"=>"d_flags_s",
					 "label"=>$lang_str['ff_for_ser'],
					 "initial"=>1,
					 "3state"=>true);

		$f[] = array("type"=>"checkbox",
		             "name"=>"d_flags_sw",
					 "label"=>$lang_str['ff_for_serweb'],
					 "initial"=>1,
					 "3state"=>true);

		$f[] = array("type"=>"checkbox",
		             "name"=>"flags_m",
					 "label"=>$lang_str['ff_multivalue'],
					 "initial"=>1,
					 "3state"=>true);

		$f[] = array("type"=>"checkbox",
		             "name"=>"flags_r",
					 "label"=>$lang_str['ff_att_reg'],
					 "initial"=>1,
					 "3state"=>true);

		$f[] = array("type"=>"checkbox",
		             "name"=>"flags_e",
					 "label"=>$lang_str['ff_att_req'],
					 "initial"=>1,
					 "3state"=>true);

		return $f;
	}
	

	/**
	 *	Format attributes for smarty
	 *	and store them to $this->smarty_attrs array
	 */
	function format_attrs(){
		global $sess, $lang, $data;
	
		$opt = array('use_pager' => true,
		             'group_by_groups' => empty($this->session['hide_groups']));
	                 
		if (is_a($this->filter, "apu_base_class")){
			$opt['filter'] = $this->filter->get_filter();
		}
		if (is_a($this->sorter, "apu_base_class")){
			$opt['order_by']   = $this->sorter->get_sort_col();
			$opt['order_desc'] = $this->sorter->get_sort_dir();
		}

		$data->set_act_row($this->filter->get_act_row());

		if (false === $at = $data->get_attr_types($opt)) return false;

		$this->pager['url']=$_SERVER['PHP_SELF']."?kvrk=".uniqid("")."&act_row=";
		$this->pager['pos']=$data->get_act_row();
		$this->pager['items']=$data->get_num_rows();
		$this->pager['limit']=$data->get_showed_rows();
		$this->pager['from']=$data->get_res_from();
		$this->pager['to']=$data->get_res_to();

		$this->smarty_attrs = array();
		foreach($at as $k => $v){
			$this->smarty_attrs[$k]	= $at[$k]->to_table_row();
			$this->smarty_attrs[$k]['translate_desc'] = (isset($this->smarty_attrs[$k]['description'][0]) and $this->smarty_attrs[$k]['description'][0] == "@");
			$this->smarty_attrs[$k]['desc_translated'] = $this->smarty_attrs[$k]['translate_desc'] ? $at[$k]->get_description() : "";
			$this->smarty_attrs[$k]['translation_lack'] = (isset($this->smarty_attrs[$k]['desc_translated'][0]) and ($this->smarty_attrs[$k]['desc_translated'][0] == "@"));
			$this->smarty_attrs[$k]['url_edit'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&edit_id=".RawURLEncode($k)."&edit=1");
			$this->smarty_attrs[$k]['url_dele'] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&edit_id=".RawURLEncode($k)."&dele=1");
			$this->smarty_attrs[$k]['url_ext'] = "";
			if ($at[$k]->apu_edit()){
				$this->smarty_attrs[$k]['url_ext'] = $sess->url($this->opt['type_spec_script']."?attrib_name=".RawURLEncode($at[$k]->get_name())."&kvrk=".uniqID(""));
			}

		}
	}
	
	/**
	 *	Read POST params and set given attribute by them
	 *
	 *	@param	Attr_type	$at
	 */
	function set_attr_type_by_post(&$at){

		$at->set_name($_POST['attr_name']);
		$at->set_type($_POST['attr_type']);
		$at->set_description($_POST['attr_label']);
		$at->set_order($_POST['attr_order']);
		$at->set_access($_POST['attr_access']);

		if (!empty($_POST['attr_new_group']))  $at->set_group($_POST['attr_new_group']);
		else                                   $at->set_group($_POST['attr_group']);
		
		if (!empty($_POST['for_ser']))      $at->set_for_ser();
		else                                $at->reset_for_ser();
		
		if (!empty($_POST['for_serweb']))   $at->set_for_serweb();
		else                                $at->reset_for_serweb();
		
		if (!empty($_POST['pr_uri']))       $at->set_for_URIs();
		else                                $at->reset_for_URIs();
		
		if (!empty($_POST['pr_user']))      $at->set_for_users();
		else                                $at->reset_for_users();
		
		if (!empty($_POST['pr_domain']))    $at->set_for_domains();
		else                                $at->reset_for_domains();
		
		if (!empty($_POST['pr_global']))    $at->set_for_globals();
		else                                $at->reset_for_globals();
		
		if (!empty($_POST['multivalue']))   $at->set_multivalue();
		else                                $at->reset_multivalue();
		
		if (!empty($_POST['registration'])) $at->set_registration();
		else                                $at->reset_registration();
		
		if (!empty($_POST['required']))     $at->set_required();
		else                                $at->reset_required();
		
	}	

	
	/**
	 *	Method perform action toggle_grp
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_toggle_grp(&$errors){

		if (empty($this->session['hide_groups'])){
			$this->session['hide_groups'] = true;
		}
		else{
			$this->session['hide_groups'] = false;
		}

		return true;
	}

	/**
	 *	Method perform action update
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_update(&$errors){
		global $data;

		/* get attribute which will be changed */
		if (false === $atr = $this->attrs->get_attr_type($this->edit_id)) return false;

		if (is_null($atr)){
			$atr = new Attr_type("", 2, "string", "", "", 0, 0, 0, 0, 0);
		}
		
		/* modify the attribute by values obtained from html form */
		$this->set_attr_type_by_post($atr);

		/* store changes to DB */
		if (false === $data->update_attr_type($atr, $this->edit_id, null)) return false;

		/* redirect to extended setting if requested */
		if ($this->ext_settings){
			$this->controler->change_url_for_reload(
				$this->opt['type_spec_script']."?attrib_name=".RawURLEncode($atr->get_name())."&kvrk=".uniqID(""));
		}

		return true;
	}

	/**
	 *	Method perform action add
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_add(&$errors){
		global $data;
		/* create new attribute */
		$atr = new Attr_type("", 2, "string", "", "", 0, 0, 0, 0, 0);

		/* modify the attribute by values obtained from html form */
		$this->set_attr_type_by_post($atr);

		/* store changes to DB */
		if (false === $data->update_attr_type($atr, null, null)) return false;

		/* redirect to extended setting if attribute use it */
		if (Attr_type::get_apu_edit($atr->get_type())){
			$this->controler->change_url_for_reload(
				$this->opt['type_spec_script']."?attrib_name=".RawURLEncode($atr->get_name())."&kvrk=".uniqID(""));
		}

		return true;
	}

	/**
	 *	Method perform action delete
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_delete(&$errors){
		global $data;
		
		if (false === $data->del_attr_type($this->edit_id, null)) return false;

		return true;
	}

	/**
	 *	Method perform action edit
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_edit(&$errors){
		if (false === $this->format_attrs()) return false;
	
		return true;
	}

	/**
	 *	Method perform action default
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_default(&$errors){
		if (false === $this->format_attrs()) return false;
	
		return true;
	}
	
	/**
	 *	check _get and _post arrays and determine what we will do 
	 */
	function determine_action(){
		if ($this->was_form_submited()){	// Is there data to process?
			if (isset($_POST['extended_settings_x'])){
				$this->ext_settings = true;
			}
			
			if (!empty($_POST['edit_id'])){
				$this->edit_id = $_POST['edit_id'];
				$this->action=array('action'=>"update",
				                    'validate_form'=>true,
									'reload'=>true);
				return;
			} 
			else{
				$this->action=array('action'=>"add",
				                    'validate_form'=>true,
									'reload'=>true);
				return;
			}
		}
		elseif (isset($_GET['edit'])){
			$this->edit_id = $_GET['edit_id'];
			$this->action=array('action'=>"edit",
			                    'validate_form'=>false,
								'reload'=>false);
			return;
		}
		elseif (isset($_GET['dele'])){
			$this->edit_id = $_GET['edit_id'];
			$this->action=array('action'=>"delete",
			                    'validate_form'=>false,
								'reload'=>true);
			return;
		}
		elseif (isset($_GET['toggle_grp'])){
			$this->action=array('action'=>"toggle_grp",
			                    'validate_form'=>false,
								'reload'=>true);
			return;
		}
		else {
			$this->action=array('action'=>"default",
			                    'validate_form'=>false,
								'reload'=>false);
			return;
		}
	}
	
	/**
	 *	create html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return null			FALSE on failure
	 */
	function create_html_form(&$errors){
		global $lang_str;
		parent::create_html_form($errors);
		
		/* get list of attributes */
		$this->attrs = &Attr_types::singleton();
		if (false === $at = $this->attrs->get_attr_types()) return false;
		if (false === $grp = $this->attrs->get_attr_groups()) return false;
		
		$this->attr_groups = $grp;
		
		$grp[] = array("label" => "< ".$lang_str['attr_grp_create_new']." >", "value" => "__new__");
		$grp_cnt = count($grp)-1;
		
		/* default values for form elements */
		if ($this->edit_id and isset($at[$this->edit_id])){
			$atr = &$at[$this->edit_id];
		}
		else{
			$atr = new Attr_type("", 2, "string", "", "", 0, 0, 0, 0, 0);
		}
		
		
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"attr_name",
									 "size"=>16,
									 "maxlength"=>32,
		                             "value"=>$atr->get_name(),
		                             "minlength"=>1,
									 "length_e"=>$lang_str['fe_not_filled_name_of_attribute']));
		
		$this->f->add_element(array("type"=>"select",
		                             "name"=>"attr_type",
									 "size"=>1,
									 "options"=>Attr_types::get_all_types(),
		                             "value"=>$atr->get_type()));

		$this->f->add_element(array("type"=>"text",
		                             "name"=>"attr_order",
									 "size"=>16,
									 "maxlength"=>5,
		                             "value"=>$atr->get_order(),
									 "valid_regex"=>"^[0-9]+$",
									 "valid_e"=>$lang_str['fe_order_is_not_number']));
		
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"attr_label",
									 "size"=>16,
									 "maxlength"=>255,
		                             "value"=>$atr->get_raw_description()));

		$this->f->add_element(array("type"=>"select",
		                             "name"=>"attr_access",
									 "size"=>1,
									 "options"=>$atr->get_access_options(),
		                             "value"=>$atr->get_access()));
		
		$this->f->add_element(array("type"=>"select",
		                             "name"=>"attr_group",
									 "size"=>1,
									 "options"=>$grp,
		                             "value"=>$atr->get_group(),
									 "extrahtml"=>"onchange='if (this.selectedIndex==".$grp_cnt."){this.form.attr_new_group.disabled=false; this.form.attr_new_group.focus();}else{this.form.attr_new_group.disabled=true;}'"));
		
		$this->f->add_element(array("type"=>"text",
		                             "name"=>"attr_new_group",
									 "size"=>16,
		                             "value"=>"",
									 "disabled"=>true));
		
		$this->f->add_element(array("type"=>"checkbox",
		                             "name"=>"for_ser",
		                             "value"=>"1",
									 "checked"=>$atr->is_for_ser()));
		
		$this->f->add_element(array("type"=>"checkbox",
		                             "name"=>"for_serweb",
		                             "value"=>"1",
									 "checked"=>$atr->is_for_serweb()));
		
		$this->f->add_element(array("type"=>"checkbox",
		                             "name"=>"pr_uri",
		                             "value"=>"1",
									 "checked"=>$atr->is_for_URIs()));
		
		$this->f->add_element(array("type"=>"checkbox",
		                             "name"=>"pr_user",
		                             "value"=>"1",
									 "checked"=>$atr->is_for_users()));
		
		$this->f->add_element(array("type"=>"checkbox",
		                             "name"=>"pr_domain",
		                             "value"=>"1",
									 "checked"=>$atr->is_for_domains()));
		
		$this->f->add_element(array("type"=>"checkbox",
		                             "name"=>"pr_global",
		                             "value"=>"1",
									 "checked"=>$atr->is_for_globals()));
		
		$this->f->add_element(array("type"=>"checkbox",
		                             "name"=>"multivalue",
		                             "value"=>"1",
									 "checked"=>$atr->is_multivalue()));
		
		$this->f->add_element(array("type"=>"checkbox",
		                             "name"=>"registration",
		                             "value"=>"1",
									 "checked"=>$atr->fill_on_register()));
		
		$this->f->add_element(array("type"=>"checkbox",
		                             "name"=>"required",
		                             "value"=>"1",
									 "checked"=>$atr->is_required()));
		
		$this->f->add_element(array("type"=>"hidden",
		                             "name"=>"edit_id",
		                             "value"=>$this->edit_id));

		if ($atr->apu_edit()){
			$this->f->add_extra_submit('extended_settings', $this->opt['form_submit_extended']);
		}

	}

	/**
	 *	validate html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return bool			TRUE if given values of form are OK, FALSE otherwise
	 */
	function validate_form(&$errors){
		global $lang_str;
		if (false === parent::validate_form($errors)) return false;
		
		if ($_POST['attr_group'] == '__new__'){
			if (empty($_POST['attr_new_group'])){
				$errors[] = $lang_str['err_at_grp_empty'];
				return false;
			}
		}
		else{
			$_POST['attr_new_group'] = null;
		}
		
		return true;
	}
	
	
	/**
	 *	add messages to given array 
	 *
	 *	@param array $msgs	array of messages
	 */
	function return_messages(&$msgs){
		global $_GET;
		
		if (isset($_GET['m_my_apu_updated']) and $_GET['m_my_apu_updated'] == $this->opt['instance_id']){
			$msgs[]=&$this->opt['msg_update'];
			$this->smarty_action="was_updated";
		}
	}

	/**
	 *	assign variables to smarty 
	 */
	function pass_values_to_html(){
		global $smarty, $sess;
		$smarty->assign_by_ref($this->opt['smarty_action'], $this->smarty_action);

		$smarty->assign_by_ref($this->opt['smarty_attrs'], $this->smarty_attrs);
		$smarty->assign_by_ref($this->opt['smarty_groups'], $this->attr_groups);
		$smarty->assign_by_ref($this->opt['smarty_pager'], $this->pager);

		$smarty->assign($this->opt['smarty_show_groups'], empty($this->session['hide_groups']));
		$smarty->assign_by_ref($this->opt['smarty_url_toggle_groups'], $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&toggle_grp=1"));

	}
	
	/**
	 *	return info need to assign html form to smarty 
	 */
	function pass_form_to_html(){
		return array('smarty_name' => $this->opt['smarty_form'],
		             'form_name'   => $this->opt['form_name'],
		             'after'       => '',
					 'before'      => '');
	}
}

?>

<?php
/**
 * Application unit filter 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_filter.php,v 1.1 2006/07/20 16:06:15 kozlik Exp $
 * @package   serweb
 */ 

/**
 *	Application unit filter 
 *
 *
 *	This application unit is used for display filter form
 *	   
 *	Configuration:
 *	--------------
 *	
 *	'form_name'					(string) default: ''
 *	 name of html form
 *	
 *	'form_submit'				(assoc)
 *	 assotiative array describe submit element of form. For details see description 
 *	 of method add_submit in class form_ext
 *	
 *	'smarty_form'				name of smarty variable - see below
 *	
 *	Exported smarty variables:
 *	--------------------------
 *	opt['smarty_form'] 			(form)			
 *	 phplib html form
 *	 
 *	
 */

class apu_filter extends apu_base_class{
	var $form_elements;
	

	/** 
	 *	return required data layer methods - static class 
	 *
	 *	@return array	array of required data layer methods
	 */
	function get_required_data_layer_methods(){
		return array();
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
	function apu_filter(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['filter_name'] =			'';

		$this->opt['on_change_callback'] =			'';

		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* name of html form */
		$this->opt['form_name'] =			'';

		$this->opt['form_submit']=array('type' => 'button',
										'text' => $lang_str['b_search']);
		
		
	}

	function set_base_apu(&$apu){
		$this->base_apu = &$apu;
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

		if (!isset($this->session['f_values'])){
			$this->session['f_values'] = array();
		}

		if (!isset($this->session['act_row'])){
			$this->session['act_row'] = 0;
		}
		
		if (isset($_GET['act_row'])){
			$this->session['act_row'] = $_GET['act_row'];
		}

	}
	
	/**
	 *	Method perform action update
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_update(&$errors){
		foreach ($this->form_elements as $k=>$v){
			if ($v['type'] == "checkbox"){
				$this->session['f_values'][$v['name']] = !empty($_POST[$v['name']]);
			}
			else{
				if (isset($_POST[$v['name']])){
					$this->session['f_values'][$v['name']] = $_POST[$v['name']];
				}
			}
		}

		$this->session['act_row'] = 0;

		if (!empty($this->opt['on_change_callback'])){
			call_user_func($this->opt['on_change_callback']);
		}

		if (!empty($this->session['get_param'])) {
			return (array)$this->session['get_param'];
		}

		return true;
	}
	
	/**
	 *	check _get and _post arrays and determine what we will do 
	 */
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
	
	/**
	 *	create html form 
	 *
	 *	@param array $errors	array with error messages
	 *	@return null			FALSE on failure
	 */
	function create_html_form(&$errors){
		parent::create_html_form($errors);
		
		$this->form_elements = $this->base_apu->get_filter_form();
		
		foreach ($this->form_elements as $k => $v){
			if (!isset($this->session['f_values'][$v['name']])){
				$this->session['f_values'][$v['name']] = null;
			}

			if ($v['type'] == "checkbox"){
				$v['checked'] = $this->session['f_values'][$v['name']];
				if (empty($v['value'])) $v['value'] = 1;	//if value is not set
			}
			else{			
				$v['value'] = $this->session['f_values'][$v['name']];
			}

			if ($v['type'] == "text" and !isset($v['maxlength'])){
				$v['maxlength'] = 32;
			}

			$this->f->add_element($v);
			$this->form_elements[$k] = $v;
		}
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
	
	function get_act_row(){
		return $this->session['act_row'];
	}
	
	function get_filter_values(){
		return $this->session['f_values'];
	}

	function set_get_param_for_redirect($str){
		$this->session['get_param']=$str;
	}

	function is_form_submited(){
		return ($this->action['action'] == "update");
	}
	
}


?>

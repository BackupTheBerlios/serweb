<?php
/**
 * Application unit sorter 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_sorter.php,v 1.1 2007/01/18 14:05:11 kozlik Exp $
 * @package   serweb
 */ 

/**
 *	Application unit sorter 
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

class apu_sorter extends apu_base_class{
	var $form_elements;
	var $col_to_sort = null;
	

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
	function apu_sorter(){
		global $lang_str;
		parent::apu_base_class();

		/* set default values to $this->opt */		
		$this->opt['sorter_name'] =			'';

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

		$session_name = empty($this->opt['sorter_name'])?
		                md5($_SERVER["PHP_SELF"]):
		                $this->opt['sorter_name'];

		if (!isset($_SESSION['apu_sorter'][$session_name])){
			$_SESSION['apu_sorter'][$session_name] = array();
		}
		
		$this->session = &$_SESSION['apu_sorter'][$session_name];

		if (!isset($this->session['reverse_order'])){
			$this->session['reverse_order'] = false;
		}
	}
	
	/**
	 *	Method perform action update
	 *
	 *	@param array $errors	array with error messages
	 *	@return array			return array of $_GET params fo redirect or FALSE on failure
	 */

	function action_update(&$errors){

		if ($this->session['sort_col'] == $this->col_to_sort){
			$this->session['reverse_order'] = !$this->session['reverse_order'];
		}
		else{
			$this->session['sort_col'] = $this->col_to_sort;
			$this->session['reverse_order'] = false;
		}

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

		$this->sort_columns = $this->base_apu->get_sorter_columns();
		if (!isset($this->session['sort_col'])){
			$this->session['sort_col'] = reset($this->sort_columns);
		}

		foreach($this->sort_columns as $v){
			if (isset($_GET['u_sort_'.$v])){
				$this->col_to_sort = $v;
				$this->action=array('action'=>"update",
				                    'validate_form'=>false,
									'reload'=>true);
				return;
			}
		}

		$this->action=array('action'=>"default",
		                    'validate_form'=>false,
							'reload'=>false);
	}
	

	/**
	 *	assign variables to smarty 
	 */
	function pass_values_to_html(){
		global $smarty, $sess;

		foreach($this->sort_columns as $v){
			$smarty->assign("url_sort_".$v, $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&u_sort_".$v."=1"));
		}
	}
		
	
	function get_sort_col(){
		return $this->session['sort_col'];
	}

	function set_sort_col($col){
		$this->session['sort_col'] = $col;
	}
	
	/**
	 * return true for descending
	 *        false for ascending sorting
	 */
	function get_sort_dir(){
		return $this->session['reverse_order'];
	}

	function set_reverse_order($dir){
		$this->session['reverse_order'] = $dir;
	}

	function set_get_param_for_redirect($str){
		$this->session['get_param']=$str;
	}

/*
	function is_form_submited(){
		return ($this->action['action'] == "update");
	}
*/	
}


?>

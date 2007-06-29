<?php
/**
 * Application unit sorter 
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_sorter.php,v 1.4 2007/06/29 08:41:31 kozlik Exp $
 * @package   serweb
 * @subpackage framework
 */ 

/**
 *	Application unit sorter 
 *
 *	This application unit is used for display filter form
 *	   
 *	<pre>
 *	Configuration:
 *	--------------
 *	
 *	'default_sort_col'			(string) default: none
 *	 Name of column, the result is initialy sorted by. If is not specified,
 *	 the first column from column list is used.
 *	
 *	'desc_order_by_default'		(bool) default: false
 *	 If true, the result is initialy sorted in descending order
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
 *	</pre>
 *	
 *	@package   serweb
 *	@subpackage framework
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

		$this->opt['default_sort_col'] =	'';
		$this->opt['desc_order_by_default'] =	false;

		$this->opt['on_change_callback'] =			'';

		
		/*** names of variables assigned to smarty ***/
		/* form */
		$this->opt['smarty_form'] =			'form';
		/* name of html form */
		$this->opt['form_name'] =			'';

		$this->opt['smarty_vars'] =			'url_sort';


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
			$this->session['reverse_order'] = $this->opt['desc_order_by_default'];
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
			if ($this->opt['default_sort_col'])
				$this->session['sort_col'] = $this->opt['default_sort_col'];
			else
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

		$sort_urls = array();

		foreach($this->sort_columns as $v){
			$sort_urls[$v] = $sess->url($_SERVER['PHP_SELF']."?kvrk=".uniqID("")."&u_sort_".$v."=1");
			$smarty->assign_by_ref($this->opt['smarty_vars']."_".$v, $sort_urls[$v]);
		}

		$smarty->assign_by_ref($this->opt['smarty_vars'], $sort_urls);
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

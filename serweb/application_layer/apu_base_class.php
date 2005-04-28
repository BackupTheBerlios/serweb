<?
/**
 * The main parent of all application units
 * 
 * @author    Karel Kozlik
 * @version   $Id: apu_base_class.php,v 1.7 2005/04/28 14:23:36 kozlik Exp $
 * @package   serweb
 */ 

/** 
 *	The main parent of all application units 
 *	
 *	Configuration:
 *	--------------
 *	instance_id			unique identificator of instance of application unit
 *	form_submit			assotiative array describe submit element of form. 
 *						for details see description of method add_submit in class form_ext
 *
 */							

class apu_base_class{
	/** associative array of application unit options */
	var $opt=array();
	
	var $action;
	/** unified number of instance of this class */
	var $instance;
	/** auth info of user with which setting we are workig. Usualy is same as $serweb_auth, only admin can change it */
	var $user_id;
	/** html form */
	var $f;
	/** name of html form when multiple shared html forms is used, variable is set by controler */
	var $form_name = null;
	/** reference to page_controler */
	var $controler;
	

	/* constructor */
	function apu_base_class(){
		global $sess_lang, $lang_str;
		$this->action="";
		/* set instance id for identification this object when multiple instances is used */
		$this->opt['instance_id']=get_class($this).apu_base_class::get_Instance();

		$this->opt['form_submit']=array('type' => 'image',
										'text' => $lang_str['b_submit'],
										'src'  => get_path_to_buttons("btn_submit.gif", $sess_lang));
	
	}
	
	/* static method - generate instance number */
	function get_Instance(){
	    static $instance_counter = 0;
		$instance_counter++;
	    return $instance_counter;
	}

	/* return required data layer methods - static class */
	function get_required_data_layer_methods(){
		return array();
	}

	/* return array of strings - required javascript files */
	function get_required_javascript(){
		return array();
	}

	/* set option $opt_name to value $val */
	function set_opt($opt_name, $val){
		$this->opt[$opt_name]=$val;
	}
	
	/* this metod is called always at begining */
	function init(){
		/* if html form is common for more APUs, reference this->f to common form */
		if ($this->controler->shared_html_form){
			/* if html form was not assignet to this APU, assign default */
			if (is_null($this->form_name)){
				sw_log("Html form was not assigned to APU ".$this->opt['instance_id'].".  Useing default.", PEAR_LOG_DEBUG);	
				$this->controler->assign_form_name('default', $this);
			}
		
			$this->f = &$this->controler->f[$this->form_name]['form'];
		}
		/* else create own form object */
		else{
			$this->f = new form_ext();
		}
	}

	function action_default(&$errors){
		return true;
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
		$this->action=array('action'=>"default",
		                    'validate_form'=>false,
							'reload'=>false);
	}
	
	/* create html form */
	function create_html_form(&$errors){
		/* if html form is shared by more APUs, add insatance_id to controler->form_apu_names array */
		if ($this->controler->shared_html_form)
			$this->controler->f[$this->form_name]['apu_names'][] = $this->opt['instance_id'];
		else{
		/* otherways form isn't shared - add hidden element to it */
			$this->f->add_element(array("type"=>"hidden",
			                             "name"=>"apu_name",
			                             "value"=>$this->opt['instance_id']));
		/* and also add submit element */
			$this->f->add_submit($this->opt['form_submit']);
		}
	}
	
	/* validate html form */
	function validate_form(&$errors){
		/* if html form isn't shared validate it, otherwise it do controler */
		if (!$this->controler->shared_html_form){
			if ($err = $this->f->validate()) {			// Is the data valid?
				$errors=array_merge($errors, $err); // No!
				return false;
			}
		}
		return true;
	}

	/* callback function when some html form is invalid */
	function form_invalid(){
	}

	/* check if form of this APU was submited */
	function was_form_submited(){
		global $_POST;
	
		/* check if is set $_POST['apu_name'] anf if it contains
		   instance_id of this APU
		 */
	
		if (isset($_POST['apu_name']) and 
			(is_array($_POST['apu_name'])?
				in_array($this->opt['instance_id'], $_POST['apu_name']):
				$_POST['apu_name']==$this->opt['instance_id']))
			return true;
		else return false;
	
	}
	
	/* add messages to given array */
	function return_messages(&$msgs){
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
	}

	/* return info need to assign html form to smarty */
	function pass_form_to_html(){
		return false;
	}
}

?>

<?
/*
 * $Id: apu_base_class.php,v 1.4 2004/09/17 17:21:47 kozlik Exp $
 */ 

/* The main parent of all application units */

/* 
   Configuration:
   --------------
   instance_id			unique identificator of instance of application unit
   form_submit			assotiative array describe submit element of form. 
   						for details see description of method add_submit in class form_ext
 */							

class apu_base_class{
	var $opt=array();	//associative array of application unit options
	var $action;
	var $instance;		//unified number of instance of this class
	var $user_id;		//auth info of user with which setting we are workig. Usualy is same as $serweb_auth, only admin can change it
	var $f; 			//html form
	var $controler; 	//reference to page_controler
	

	/* constructor */
	function apu_base_class(){
		$this->action="";
		/* set instance id for identification this object when multiple instances is used */
		$this->opt['instance_id']=get_class($this).apu_base_class::get_Instance();
		$this->opt['form_submit']=array('type'=>'hidden');
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
		/* if html form is common for all apu, reference this->f to controler form */
		if ($this->controler->opt['shared_html_form']) $this->f = &$this->controler->f;
		/* else create own form object */
		else	$this->f = new form_ext;
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
		if ($this->controler->opt['shared_html_form'])
			$this->controler->form_apu_names[] = $this->opt['instance_id'];
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
		if (!$this->controler->opt['shared_html_form']){
			if ($err = $this->f->validate()) {			// Is the data valid?
				$errors=array_merge($errors, $err); // No!
				return false;
			}
		}
		return true;
	}

	/* check if form of this APU was submited */
	function was_form_submited(){
		global $_POST;
	
		/* check if is set $_POST['okey_x'] and if $_POST['apu_name'] contains
		   instance_id of this APU
		 */
	
		if (isset($_POST['okey_x']) and $_POST['okey_x'] and 
			isset($_POST['apu_name']) and 
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
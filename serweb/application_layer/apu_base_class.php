<?
/*
 * $Id: apu_base_class.php,v 1.1 2004/08/25 10:19:48 kozlik Exp $
 */ 

/* The main parent of all application units */
 
class apu_base_class{
	var $opt=array();	//associative array of application unit options
	var $action;
	var $instance;		//unified number of instance of this class
	var $user_id;		//auth info of user with which setting we are workig. Usualy is same as $serweb_auth, only admin can change it

	/* constructor */
	function apu_base_class(){
		$this->action="";
		/* set instance id for identification this object when multiple instances is used */
		$this->opt['instance_id']=get_class($this).apu_base_class::get_Instance();
	}
	
	/* static class - generate instance number */
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
	}
	
	/* check _get and _post arrays and determine what we will do */
	function determine_action(){
	}
	
	/* realize action */
	function execute(&$errors){
	}
	
	/* add messages to given array */
	function return_messages(&$msgs){
	}

	/* assign variables to smarty */
	function pass_values_to_html(){
	}
}

?>
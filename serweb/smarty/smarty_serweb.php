<?
define ('SMARTY_DIR', dirname(__FILE__).'/');
require(SMARTY_DIR.'Smarty.class.php');


class Smarty_Serweb extends Smarty {

	function Smarty_Serweb() {
		// Class Constructor. These automatically get set with each new instance.
		$this->Smarty();

		//set smarty directories
		$this->template_dir = SMARTY_DIR.'../templates/';
		$this->compile_dir =  SMARTY_DIR.'../templates/templates_c/';
		$this->config_dir =   SMARTY_DIR.'../templates/configs/';
		$this->cache_dir =    SMARTY_DIR.'../templates/cache/';

	}


	/* function assign phplib form $form to smarty under name $name
	   $start_arg is associative array of arguments of method $form->start
	   $finish_arg is associative array of arguments of method $form->finish
	*/
	
	function assign_phplib_form($name, $form, $start_arg = array(), $finish_arg = array()){

		/* assign default values to args */		
		if (!isset($start_arg['jvs_name']))		$start_arg['jvs_name']="";
		if (!isset($start_arg['method']))		$start_arg['method']="";
		if (!isset($start_arg['action']))		$start_arg['action']="";
		if (!isset($start_arg['target']))		$start_arg['target']="";
		if (!isset($start_arg['form_name']))	$start_arg['form_name']="";
		
		if (!isset($finish_arg['after']))	$finish_arg['after']="";
		if (!isset($finish_arg['before']))	$finish_arg['before']="";

		/* create associative array of form elements and begin and end tags of form */

		/* add begin tag to assoc array */
		$f['start']=$form->get_start($start_arg['jvs_name'], $start_arg['method'], $start_arg['action'], $start_arg['target'], $start_arg['form_name']);

		/* add elements of form to assoc array */
		if (is_array($form->elements)){
			foreach($form->elements as $nm => $el){
				/* if element is radio we must add form elements for each radio button */
				if (get_class($el['ob']) == 'of_radio'){
					/* values of radio buttos shold be stored in 'options' property, if they are not -> ignore this element */
					if (isset($el['ob']->options) and is_array($el['ob']->options)){
						foreach($el['ob']->options as $opt){
							$f[$nm.'_'.$opt['value']] = $form->get_element($nm, $opt['value']);
						}
					}
				}
				else {
					/* for all others elements simply add them to $f array */
					$f[$nm] = $form->get_element($nm);
				}
			}
		}

		/* add end tag to assoc array */
		$f['finish']=$form->get_finish($finish_arg['after'], $finish_arg['before']);

		$this->assign($name, $f);	
	}
	
}

?>
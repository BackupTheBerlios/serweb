<?
/*
 * $Id: get_pass.php,v 1.6 2006/09/08 12:27:33 kozlik Exp $
 */

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session");

$_required_modules = array('auth');

$_required_apu = array('apu_forgotten_password');						   

require "prepend.php";

$fp=new apu_forgotten_password();
$fp->set_opt('form_name', 'form1');
$fp->set_opt("auth_class", "phplib_Auth");
//$register->set_opt('form_submit', array('type' => 'image',
//										'text' => $lang_str['b_submit'],
//										'src'  => get_path_to_buttons("btn_submit.gif", $sess_lang)));

										

$controler->add_apu($fp);
$controler->set_template_name('ur_get_pass.tpl');
$controler->start();

?>

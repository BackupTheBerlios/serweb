<?
/*
 * $Id: get_pass.php,v 1.4 2005/12/22 16:58:56 kozlik Exp $
 */

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session");

$_required_modules = array('auth');

$_required_apu = array('apu_forgotten_password');						   

require "prepend.php";

$fp=new apu_forgotten_password();
$fp->set_opt('form_name', 'form1');
//$register->set_opt('form_submit', array('type' => 'image',
//										'text' => $lang_str['b_submit'],
//										'src'  => get_path_to_buttons("btn_submit.gif", $sess_lang)));

										
$config->html_headers = array_merge($config->html_headers, 
		array('<style type="text/css">
				body, .swMain{
					background: white;
				}
			   </style>'));
										

$controler->add_apu($fp);
$controler->set_template_name('ur_get_pass.tpl');
$controler->start();

?>

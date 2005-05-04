<?
/*
 * $Id: get_pass.php,v 1.2 2005/05/04 15:35:37 kozlik Exp $
 */

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session");

$_required_apu = array('apu_forgotten_password');						   

require "prepend.php";

$fp=new apu_forgotten_password();
$fp->set_opt('form_name', 'form1');
//$register->set_opt('form_submit', array('type' => 'image',
//										'text' => $lang_str['l_submit'],
//										'src'  => $config->img_src_path."butons/btn_submit.gif"));

										
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

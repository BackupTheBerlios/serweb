<?
/*
 * $Id: index.php,v 1.4 2006/01/11 11:57:17 kozlik Exp $
 */

$_data_layer_required_methods=array('get_did_by_realm');
$_phplib_page_open = array("sess" => "phplib_Session");

$_required_modules = array('registration');

$_required_apu = array('apu_registration');						   

require "prepend.php";

$form_submit=array('type' => 'image',
				   'text' => $lang_str['b_register'],
				   'src'  => get_path_to_buttons("btn_register.gif", $_SESSION['lang']));


$did = $data->get_did_by_realm($config->domain, null);
if ((false === $did) or is_null($did)) die("Can't find domain ID");

$register=new apu_registration();
$register->set_opt('form_name', 'form1');
$register->set_opt('form_submit', $form_submit);
$register->set_opt('terms_file', "terms.txt");
$register->set_opt('mail_file', "mail_register.txt");
$register->set_opt('mail_file_conf', "mail_register_conf.txt");
$register->set_opt('confirmation_script', "reg/confirmation.php");
$register->set_opt('register_in_domain', $did);

										
$config->html_headers = array_merge($config->html_headers, 
		array('<style type="text/css">
				body, .swMain{
					background: white;
				}
			   </style>'));
										

$controler->add_apu($register);
$controler->set_template_name('registration.tpl');
$controler->start();


?>

<?php
/**
 *	Registration page
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: index.php,v 1.9 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$_data_layer_required_methods=array('get_did_by_realm');
$_phplib_page_open = array("sess" => "phplib_Session");

$_required_modules = array('registration');

$_required_apu = array('apu_registration');						   

/** include all others necessary files */
require "prepend.php";

$form_submit=array('type' => 'image',
				   'text' => $lang_str['b_register'],
				   'src'  => get_path_to_buttons("btn_register.gif", $_SESSION['lang']));


if (!$config->multidomain) {
	$did = $config->default_did;
}
else{
	$did = $data->get_did_by_realm($config->domain, null);

	if ((false === $did) or is_null($did)) {
		ErrorHandler::add_error("Can't obtain domain ID of domain you want register in (".$config->domain."). See the serweb log for more info.");

		$controler->set_template_name('_default.tpl');
		$controler->start();
		exit;
	}
}

do {
	$opt = array("did"=>$did);
	if (false === $addr = Attributes::get_attribute($config->attr_names['contact_email'], $opt)) break;
			
	if (!$addr) $addr = $config->mail_header_from;
	$smarty->assign('infomail', $addr);
} while (false);


$register=new apu_registration();
$register->set_opt('form_name', 'form1');
$register->set_opt('form_submit', $form_submit);
$register->set_opt('terms_file', "terms.txt");
$register->set_opt('mail_file', "mail_register.txt");
$register->set_opt('mail_file_conf', "mail_register_conf.txt");
$register->set_opt('confirmation_script', "reg/confirmation.php");
$register->set_opt('register_in_domain', $did);
$register->set_opt('set_lang_attr', $_SESSION['lang']);

										

$controler->add_apu($register);
$controler->set_template_name('registration.tpl');
$controler->start();


?>

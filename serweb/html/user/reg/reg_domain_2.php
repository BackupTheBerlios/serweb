<?php
/**
 *	Self domain registration - step 2
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: reg_domain_2.php,v 1.2 2007/09/27 15:46:23 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$_data_layer_required_methods=array('get_did_by_realm');
$_phplib_page_open = array("sess" => "phplib_Session");

$_required_modules = array('registration');

$_required_apu = array('apu_registration');						   

/** include all others necessary files */
require "prepend.php";

if (!$config->allow_self_domain_register) 
    die('Self domain registration is not allowed');

$form_submit=array('type' => 'image',
				   'text' => $lang_str['b_register'],
				   'src'  => get_path_to_buttons("btn_register.gif", $_SESSION['lang']));

/* if domain for hostin is not set */
if (empty($_SESSION['register_new_domain'])){
    /* write message to log */
    ErrorHandler::log_errors(PEAR::raiseError("Domain for hosting is not set!!"));

    /* display the empty page (with error message) */
    $controler->start();
    /* and exit */
    exit (0);
}


$register=new apu_registration();
$register->set_opt('form_name', 'form1');
$register->set_opt('form_submit', $form_submit);
$register->set_opt('terms_file', "terms.txt");
$register->set_opt('mail_file', "mail_register.txt");
$register->set_opt('mail_file_conf', "mail_register_conf.txt");
$register->set_opt('confirmation_script', "reg/confirmation.php");
$register->set_opt('create_new_domain', $_SESSION['register_new_domain']);
$register->set_opt('set_lang_attr', $_SESSION['lang']);

										

$controler->add_apu($register);
$controler->set_template_name('reg_domain_2.tpl');
$controler->start();


?>

<?php
/**
 *	Self domain registration - step 1
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: reg_domain_1.php,v 1.2 2007/09/27 15:46:23 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$_data_layer_required_methods=array();
$_phplib_page_open = array("sess" => "phplib_Session");

$_required_modules = array('multidomain');

$_required_apu = array('apu_domain_dns_validator');						   

/** include all others necessary files */
require "prepend.php";


if (!$config->allow_self_domain_register) 
    die('Self domain registration is not allowed');

$form_submit=array('type' => 'image',
				   'text' => $lang_str['b_next'],
				   'src'  => get_path_to_buttons("btn_next.gif", $_SESSION['lang']));





$dv=new apu_domain_dns_validator();
$dv->set_opt('form_name', 'form1');
$dv->set_opt('form_submit', $form_submit);
$dv->set_opt('redirect_on_update', 'reg_domain_2.php');
$dv->set_opt('set_session_var', 'register_new_domain');

$controler->add_apu($dv);
$controler->set_template_name('reg_domain_1.tpl');
$controler->start();


?>

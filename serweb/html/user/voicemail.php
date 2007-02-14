<?php
/**
 *	Customize voicemail greetings
 * 
 *	@author     Karel Kozlik
 *	@version    $Id: voicemail.php,v 1.5 2007/02/14 16:36:40 kozlik Exp $
 *	@package    serweb
 *	@subpackage user_pages
 */ 

$_data_layer_required_methods=array();

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Auth");

$_required_modules = array('voicemail');

$_required_apu = array('apu_voicemail'); 

/** include all others necessary files */
require "prepend.php";

$vm			= new apu_voicemail();

$page_attributes['user_name'] = get_user_real_name($_SESSION['auth']->get_logged_user());


$controler->add_apu($vm);
$controler->set_template_name('u_voicemail.tpl');
$controler->start();

?>

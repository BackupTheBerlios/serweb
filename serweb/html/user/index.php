<?php
/*
 * $Id: index.php,v 1.1 2005/04/21 15:09:46 kozlik Exp $
 */ 

$_phplib_page_open = array("sess" => "phplib_Session");

$_required_apu = array('apu_login'); 

require "prepend.php";

$login	= new apu_login();

unset ($page_attributes['tab_collection']);
$page_attributes['logout']=false;
$smarty->assign('domain',$config->domain);


$controler->add_apu($login);

$controler->set_template_name('u_index.tpl');
$controler->start();


?>

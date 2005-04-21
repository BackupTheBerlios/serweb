<?php

$_phplib_page_open = array("sess" => "phplib_Session");

require "prepend.php";

$cfg=new stdclass();
$cfg->img_src_path                     = $config->img_src_path;

$smarty->assign_by_ref("cfg", $cfg);

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('u_sending_im.tpl');

?>

<?
/*
 * $Id: finish.php,v 1.7 2004/08/10 17:33:50 kozlik Exp $
 */

$_phplib_page_open = array("sess" => "phplib_Session");

require "prepend.php";

/* ----------------------- HTML begin ---------------------- */
print_html_head();
print_html_body_begin($page_attributes);

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign('domain',$config->domain);
$smarty->assign('sip_address',$_GET['sip_address']);

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('ur_finish.tpl');
?>
<?print_html_body_end();?>
</html>
<?page_close();?>

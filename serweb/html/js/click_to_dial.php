<?
/*
 * $Id: click_to_dial.php,v 1.2 2004/08/09 12:21:27 kozlik Exp $
 */

$_SERWEB = array();
$_SERWEB["serwebdir"]  = "../";
require($_SERWEB["serwebdir"] . "main_prepend.php");

put_headers();

if (isset($_GET['target']) and $_GET['target']) $target=$_GET['target'];
else $target=$config->ctd_target;

if (isset($_GET['uri']) and $_GET['uri']) $uri=$_GET['uri'];
else $uri=$config->ctd_uri;

click_to_dial($target, $uri, $errors);

/* ----------------------- HTML begin ---------------------- */ 
print_html_head();

?>
<body class="swPopUpWin">
<?
$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$smarty->assign_by_ref('parameters', $page_attributes);

$smarty->display('ctd_popup.tpl');

?>
</body>
</html>

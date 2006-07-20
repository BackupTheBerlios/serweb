<?
/*
 * $Id: click_to_dial.php,v 1.5 2006/07/20 18:44:39 kozlik Exp $
 */

require("../set_dirs.php");

$_phplib_page_open = array("sess" => "phplib_Session");

require($_SERWEB["serwebdir"] . "main_prepend.php");
require($_SERWEB["serwebdir"] . "load_phplib.php");

phplib_load();


if (empty($serweb_auth->uname) or empty($serweb_auth->domain)){
	die ('not logged in');
}

if (isset($_GET['target']) and $_GET['target']) $target=$_GET['target'];
else $target=$config->ctd_target;

if (empty($_GET['default_caller'])) $uri = "sip:".$serweb_auth->uname."@".$serweb_auth->domain;
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

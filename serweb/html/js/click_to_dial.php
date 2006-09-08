<?
/*
 * $Id: click_to_dial.php,v 1.6 2006/09/08 12:27:32 kozlik Exp $
 */

require("../set_dirs.php");

$_phplib_page_open = array("sess" => "phplib_Session");

require($_SERWEB["serwebdir"] . "main_prepend.php");
require($_SERWEB["serwebdir"] . "load_phplib.php");

phplib_load();

if (!$_SESSION['auth']->is_authenticated()){
	die ('not logged in');
}

if (isset($_GET['target']) and $_GET['target']) $target=$_GET['target'];
else $target=$config->ctd_target;

if (empty($_GET['default_caller'])) {
	$current_user = $_SESSION['auth']->get_logged_user();
	$uri = $current_user->get_uri();
}
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

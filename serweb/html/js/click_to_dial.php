<?
/*
 * $Id: click_to_dial.php,v 1.1 2004/03/24 21:39:46 kozlik Exp $
 */

$_SERWEB = array();
$_SERWEB["serwebdir"]  = "../";
require($_SERWEB["serwebdir"] . "main_prepend.php");

put_headers();

if (!$target) $target=$config->ctd_target;
if (!$uri) $uri=$config->ctd_uri;

click_to_dial($target, $uri, $errors);

/* ----------------------- HTML begin ---------------------- */ 
print_html_head();
?>
<body class="swPopUpWin">
<br>
<?
if ($errors) print_errors($errors);	// Display error
else echo "<div align=\"center\">dial succeeded</div>";
?>
</body>
</html>

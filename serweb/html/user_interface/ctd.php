<?
/*
 * $Id: ctd.php,v 1.3 2004/03/11 22:30:00 kozlik Exp $
 */
require "prepend.php";

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

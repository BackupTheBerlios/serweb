<?
/*
 * $Id: ctd.php,v 1.1 2003/04/29 08:12:57 kozlik Exp $
 */
require "prepend.php";

put_headers();

if (!$target) $target=$config->ctd_target;
if (!$uri) $uri=$config->ctd_uri;

click_to_dial($target, $uri, $errors);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>iptel.org -- My Account </title>
<?print_html_head();?>
</head>
<body bgcolor="#B1C9DC" text="#000000" link="#33CCFF" vlink="#33CCCC" alink="#33FFFF" MARGINHEIGHT="0" MARGINWIDTH="0" leftmargin="0" topmargin="0">
<br>
<?
if ($errors) print_errors($errors);	// Display error
else echo "<div align=\"center\">dial succeeded</div>";
?>


</body>
</html>

<?
/*
 * $Id: stun_applet.php,v 1.1 2002/11/18 21:56:07 kozlik Exp $
 */

require "prepend.php";
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>iptel.org -- FW/NAT detection applet</title>
<?print_html_head();?>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
 
<applet
  codebase = "."
  code     = "<? echo $config->stun_class; ?>"
<? echo $config->stun_archive?"  archive  = \"".$config->stun_archive."\"\n":""; ?>
  width    = "<? echo $config->stun_applet_width; ?>"
  height   = "<? echo $config->stun_applet_height; ?>"
  hspace   = "0"
  vspace   = "0"
  align    = "middle"
>

<?if (is_array($config->stun_applet_param)){
	foreach ($config->stun_applet_param as $val){?>
<param name = "<? echo $val->name; ?>" value = "<? echo $val->value; ?>">
<?	}
}?>

</applet>


</body>
</html>

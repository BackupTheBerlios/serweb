<?
/*
 * $Id: stun_applet.php,v 1.4 2004/08/09 12:21:28 kozlik Exp $
 */

require "prepend.php";

/* ----------------------- HTML begin ---------------------- */ 
print_html_head($config->realm." -- FW/NAT detection applet");
?>
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

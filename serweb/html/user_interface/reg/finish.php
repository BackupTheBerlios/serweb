<?
/*
 * $Id: finish.php,v 1.2 2002/09/10 15:59:35 kozlik Exp $
 */

require "prepend.php";
require "../../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session"));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>iptel.org, the IP Telephony Site</title>
<?print_html_head();?>
</head>
<?
	print_html_body_begin();
	echo "<br>";
	print_message($message);
?>


<span class="txt_norm">
Thank you for registering with iptel.org.<br>
<br>
Your application was forwarded for approval.<br>
Expect a confirmation message shortly.<br>
<br>
We are reserving the following SIP address for you: <?echo $sip_address;?>.<br>
<br>
If you have any further questions please feel free to send<br>
an email to <a href="mailto:info@iptel.org">info@iptel.org</a>.<br>
</span>
<br>

<br>
<hr>
<div align="center" class="txt_norm">Back to <a href="<?$sess->purl("../index.php");?>">login form</a>. </div>
<hr>

<?print_html_body_end();?>
</html>
<?page_close();?>

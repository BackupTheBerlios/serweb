<?
/*
 * $Id: finish.php,v 1.6 2004/08/09 11:37:12 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session"));

/* ----------------------- HTML begin ---------------------- */
print_html_head();
print_html_body_begin($page_attributes);
?>

<p>
Thank you for registering with <?echo $config->realm;?>.<br>
<br>
Your application was forwarded for approval.<br>
Expect a confirmation message shortly.<br>
<br>
We are reserving the following SIP address for you: <?echo $sip_address;?>.<br>
<br>
If you have any further questions please feel free to send<br>
an email to <a href="mailto:<?echo $config->infomail;?>"><?echo $config->infomail;?></a>.<br>
<p>
<br>

<br>
<hr>
<div align="center">Back to <a href="<?$sess->purl("../index.php");?>">login form</a>.</div>
<hr>

<?print_html_body_end();?>
</html>
<?page_close();?>

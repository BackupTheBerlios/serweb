<?
require "prepend.php";
require "../../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session"));

do{
	if (isset($okey_x)){								// Is there data to process?
		$db = connect_to_db();
		if (!$db){ $errors[]="can´t connect to sql server"; break;}
	
		$q="select password, email_address from ".$config->table_subscriber." where user_id='$uname'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		if (!MySQL_Num_Rows($res)) {$errors[]="Sorry, '$uname' is not a registered username! Please try again."; break;}
		$row=MySQL_Fetch_Object($res);
	
		$mail_body=str_replace("#password#", $row->password, $config->mail_forgot_pass);
		
		if (!send_mail($row->email_address, $config->forgot_pass_subj, $mail_body)){
			$errors[]="Sorry, there was an error when sending mail. Please try again later."; break;
		}

        Header("Location: ".$sess->url("../index.php?kvrk=".uniqID("")."&message=".RawURLEncode("Your password was send to your email address")));
		page_close();
		exit;
	}
}while (false);

$f = new form;                   // create a form object

$f->add_element(array("type"=>"text",
                             "name"=>"uname",
							 "size"=>20,
							 "maxlength"=>50,
                             "value"=>"",
							 "minlength"=>1,
							 "length_e"=>"you must fill username",
							 "extrahtml"=>"autocomplete'off' style='width:250px;'"));
$f->add_element(array("type"=>"submit",
                             "name"=>"okey",
                             "src"=>$config->img_src_path."butons/b_get_pass.gif",
							 "extrahtml"=>"alt='get password'"));
	

if ($okey_x){							//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

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
	print_errors($errors);                    // Display error
	print_message($message);
?>


<?$f->start("form");				// Start displaying form?>

<table width="550" border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" width="1" height="50"></td></tr>

<tr bgcolor="#B1C9DC">
<td><center><h2><font face="Arial" color="#000000">Forgot Password?</font></h2></center>
<font face="Arial" size="-1" color="#000000">
If you have forgotten your password, please enter your username in the form below.
An email containing your password will then be sent to the email-address you have
registered with!
</font>
</td>
</tr>

<tr><td><hr size=1></td></tr>

<tr><td align="center">
	<table border="0" cellspacing="0" cellpadding="0"><tr>
	<td>Username:</td>
	<td width="5"><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" width="5" height="1"></td>
	<td><?$f->show_element("uname");?></td>
	<td width="10"><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" width="10" height="1"></td>
	<td><?$f->show_element("okey");?></td>
	</tr></table>
</td></tr>

<tr><td><hr size=1></td></tr>

<tr><td><img src="<?echo $config->img_src_path;?>title/white_pixel.gif" width="1" height="50"></td></tr>

<tr><td><hr size=1></td></tr>

<tr><td align="center">Back to <a href="<? $sess->purl("../index.php");?>">login form</a>.</td></tr>

<tr><td><hr size=1></td></tr>

</table>


<?$f->finish();					// Finish form?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

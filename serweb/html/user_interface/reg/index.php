<?
/*
 * $Id: index.php,v 1.6 2003/10/15 10:06:19 kozlik Exp $
 */

require "prepend.php";
require "../../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session"));

do{
	$f = new form;                   // create a form object

	$opt=get_time_zones($errors);
	$options[]=array("label"=>"--- please select your timezone ---","value"=>"");
	foreach ($opt as $v) $options[]=array("label"=>$v,"value"=>$v);

	$f->add_element(array("type"=>"select",
								 "name"=>"timezone",
								 "options"=>$options,
								 "size"=>1,
	                             "valid_e"=>"select your timezone please",
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"uname",
								 "size"=>23,
								 "maxlength"=>50,
	                             "value"=>"",
								 "minlength"=>1,
								 "length_e"=>"you must fill username",
	                             "valid_regex"=>$reg_validate_username,
	                             "valid_e"=>"username does not follow suggested conventions",
								 "extrahtml"=>"autocomplete'off' style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"passwd",
	                             "value"=>"",
								 "size"=>23,
								 "maxlength"=>25,
								 "pass"=>1,
								 "minlength"=>1,
								 "length_e"=>"you must fill password",
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"passwd_r",
	                             "value"=>"",
								 "size"=>23,
								 "maxlength"=>25,
								 "pass"=>1,
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"fname",
								 "size"=>23,
								 "maxlength"=>25,
	                             "value"=>"",
								 "minlength"=>1,
								 "length_e"=>"you must fill your first name",
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"lname",
								 "size"=>23,
								 "maxlength"=>45,
	                             "value"=>"",
								 "minlength"=>1,
								 "length_e"=>"you must fill your last name",
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"email",
								 "size"=>23,
								 "maxlength"=>50,
	                             "value"=>"",
	                             "valid_regex"=>$reg_validate_email,
	                             "valid_e"=>"not valid email address",
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"phone",
								 "size"=>23,
								 "maxlength"=>15,
	                             "value"=>"",
								 "extrahtml"=>"style='width:250px;'"));
	$f->add_element(array("type"=>"textarea",
	                             "name"=>"terms",
	                             "value"=>$config->terms_and_conditions,
								 "rows"=>8,
								 "cols"=>38,
	                             "wrap"=>"soft",
								 "extrahtml"=>"style='width:415px;'"));
	$f->add_element(array("type"=>"checkbox",
	                             "name"=>"accept",
	                             "value"=>1,
								 "extrahtml"=>"style=''"));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_register.gif",
								 "extrahtml"=>"alt='register'"));

	if (isset($okey_x)){								// Is there data to process?
		$db = connect_to_db();
		if (!$db){ $errors[]="can´t connect to sql server"; break;}

		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

		if ($passwd and ($passwd != $passwd_r)){
			$errors[]="passwords not match"; break;
		}

		if (!$accept){
			$errors[]="You don't accept terms and conditions"; break;
		}

			/* Process data */           // Data ok;

		$q="select count(*) from ".$config->table_subscriber." where lower(username)=lower('$uname')";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		$row=MySQL_Fetch_Row($res);
		if ($row[0]) {$errors[]="Sorry, the user name '$uname' has already been chosen. Try again."; break;}

		$q="select count(*) from ".$config->table_pending." where lower(username)=lower('$uname')";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		$row=MySQL_Fetch_Row($res);
		if ($row[0]) {$errors[]="Sorry, the user name '$uname' has already been chosen. Try again."; break;}

		$confirm=md5(uniqid(rand()));

		if (!add_user_to_subscriber($uname, $passwd, $fname, $lname, $phone, $email, $timezone, $confirm, $config->table_pending, $errors)) break;

		$sip_address="sip:".$uname."@".$config->default_domain;

		$mail_body=str_replace("#confirm#", $confirm, $config->mail_register);
		$mail_body=str_replace("#sip_address#", $sip_address, $mail_body);

		if (!send_mail($email, $config->register_subj, $mail_body)){
			$errors[]="Sorry, there was an error when sending mail. Please try again later."; break;
		}

        Header("Location: ".$sess->url("finish.php?sip_address=".RawURLEncode($sip_address)));
		page_close();
		exit;
	}
}while (false);



if ($okey_x){							//data isn't valid or error in sql
	$terms=$config->terms_and_conditions;
	$f->load_defaults();				// Load form with submitted data
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?echo $config->title;?></title>
<?print_html_head();?>
</head>
<?
	print_html_body_begin();
	echo "<br>";
	print_message($message);
?>


<span class="txt_norm">To register, please fill out the form below and click the
submit button at the bottom of the page. <br>An email message will be sent to you
confirming your registration. Please contact
<A HREF=mailto:<?echo $config->regmail;?>><?echo $config->regmail;?></A> <br>
if you have any questions concerning registration and our free trial SIP services.</span>
<br>
<?print_errors($errors);                    // Display error?>
<br>
<?$f->start("form");				// Start displaying form?>

<table border="0" cellspacing="0" cellpadding="4" align="center" bgcolor="#B1C9DC"><tr><td>
	<table border="0" cellspacing="0" cellpadding="0" align="center" >
	<tr>
	<td width="160" align="right" class="f12b">first name:</td>
	<td width="5">&nbsp;</td>
	<td width="250"><?$f->show_element("fname");?></td>
	</tr>
	<tr>
	<td width="160" align="right" class="f12b">last name:</td>
	<td width="5">&nbsp;</td>
	<td width="250"><?$f->show_element("lname");?></td>
	</tr>
	<tr>
	<td width="160" align="right" class="f12b">email:</td>
	<td width="5">&nbsp;</td>
	<td width="250"><?$f->show_element("email");?></td>
	</tr>
	<tr>
	<td width="160" colspan="2">&nbsp;</td>
	<td class="f12i" width="250">Address to which a subscription confirmation request will be sent. (If an invalid address is given, no confirmation will be sent and no SIP account will be created.)</td>
	</tr>
	<tr>
	<td width="160" align="right" class="f12b">phone:</td>
	<td width="5">&nbsp;</td>
	<td width="250"><?$f->show_element("phone");?></td>
	</tr>
	<tr>
	<td width="160" align="right" class="f12b">your timezone:</td>
	<td width="5">&nbsp;</td>
	<td width="250"><?$f->show_element("timezone");?></td>
	</tr>
	<tr>
	<td width="160" colspan="2">&nbsp;</td>
	<td class="f12i" width="250">This is your PSTN phone number where you can be reached.</td>
	</tr>
	<tr>
	<td width="160" align="right" class="f12b">pick your user name:</td>
	<td width="5">&nbsp;</td>
	<td width="250"><?$f->show_element("uname");?></td>
	</tr>
	<tr>
	<td width="160" colspan="2">&nbsp;</td>
	<td class="f12i" width="250">Your SIP address will be username@<?echo $config->default_domain;?>. Indicate only the username part of the address. It may be either a numerical address starting with '8' (e.g., "8910") or a lower-case alphanumerical address starting with an alphabetical character (e.g., john.doe01). Do not forget your username -- you will need it to configure your phone! </td>
	</tr>
	<tr>
	<td width="160" align="right" class="f12b">pick password:</td>
	<td width="5">&nbsp;</td>
	<td width="250"><?$f->show_element("passwd");?></td>
	</tr>
	<tr>
	<td width="160" colspan="2">&nbsp;</td>
	<td class="f12i" width="250">Do not forget your password -- you will need it to configure your phone!</td>
	</tr>
	<tr>
	<td width="160" align="right" class="f12b">confirmation password:</td>
	<td width="5">&nbsp;</td>
	<td width="250"><?$f->show_element("passwd_r");?></td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3" class="f12b">terms and conditions</td></tr>
	<tr><td colspan="3"><?$f->show_element("terms");?></td></tr>
	<tr><td colspan="3"><?$f->show_element("accept");?> I accept</td></tr>
	<tr><td colspan="3" align="right"><?$f->show_element("okey");?>&nbsp;&nbsp;&nbsp;</td></tr>
	</table>
</td></tr></table>
<?$f->finish("
	if (f.passwd.value!=f.passwd_r.value){
		alert('passwords not match');
		f.passwd.focus();
		return (false);
	}

	if (!f.accept.checked){
		alert('You don\'t accept terms and conditions');
		f.accept.focus();
		return (false);
	}
");					// Finish form?>

<br>
<hr>
<div align="center" class="txt_norm">Back to <a href="<?$sess->purl("../index.php");?>">login form</a>. </div>
<hr>

<?print_html_body_end();?>
</html>
<?page_close();?>

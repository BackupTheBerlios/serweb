<?
/*
 * $Id: get_pass.php,v 1.8 2004/03/11 22:30:00 kozlik Exp $
 */

require "prepend.php";
require "../../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session"));

do{
	if (isset($okey_x)){								// Is there data to process?
		$db = connect_to_db();
		if (!$db){ $errors[]="cannot connect to sql server"; break;}

		$q="select phplib_id, email_address from ".$config->table_subscriber." where username='$uname' and domain='$config->realm'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		if (!MySQL_Num_Rows($res)) {$errors[]="Sorry, '$uname' is not a registered username! Please try again."; break;}
		$row=MySQL_Fetch_Object($res);

		$pre_uid=$row->phplib_id;
		$pre_uid_expires=time()+$config->pre_uid_expires;

		$my_sess=new phplib_Session();
		$my_sess->set_container();
		$my_sess->name=$my_sess->classname;
		$my_sess->id = $my_sess->that->ac_newid(md5(uniqid($my_sess->magic)), $my_sess->name);
		$my_sess->register("pre_uid");
		$my_sess->register("pre_uid_expires");
		$my_sess->freeze();

		$mail_body=str_replace("#session#", $my_sess->name."=".$my_sess->id, $config->mail_forgot_pass);

		if (!send_mail($row->email_address, $config->forgot_pass_subj, $mail_body)){
			$errors[]="Sorry, there was an error when sending mail. Please try again later."; break;
		}

        Header("Location: ".$sess->url("../index.php?kvrk=".uniqID("")."&message=".RawURLEncode("Login information was send to your email address")));
		page_close();
		exit;

//		echo "oook/~iptel/user_interface/my_account.php?".$my_sess->name."=".$my_sess->id;
//		exit;
/*
		$q="select password, email_address from ".$config->table_subscriber." where username='$uname'";
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
*/	}
}while (false);

$f = new form;                   // create a form object

$f->add_element(array("type"=>"text",
                             "name"=>"uname",
							 "size"=>20,
							 "maxlength"=>50,
                             "value"=>"",
							 "minlength"=>1,
							 "length_e"=>"you must fill username",
							 "extrahtml"=>"autocomplete='off' style='width:250px;'"));
$f->add_element(array("type"=>"submit",
                             "name"=>"okey",
                             "src"=>$config->img_src_path."butons/b_get_pass.gif",
							 "extrahtml"=>"alt='get password'"));


if (isset($okey_x)){							//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */ 
print_html_head();
print_html_body_begin($page_attributes);
?>


<div class="swForgotPassw">
	<h2><font face="Arial" color="#000000">Forgot Password?</font></h2>
	If you have forgotten your password, please enter your username in the form below.
	An email containing your password will then be sent to the email-address you have
	registered with!
</div>

<hr size=1>

<div class="swForm swHorizontalForm">
<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0"><tr>
	<td><label for="uname">Username:</label></td>
	<td><?$f->show_element("uname");?></td>
	<td><?$f->show_element("okey");?></td>
	</tr></table>
<?$f->finish();					// Finish form?>
</div>

<hr size=1>

<div align="center">Back to <a href="<?$sess->purl("../index.php");?>">login form</a>.</div>


<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

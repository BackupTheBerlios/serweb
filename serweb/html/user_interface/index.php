<?
/*
 * $Id: index.php,v 1.13 2004/03/25 21:13:33 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session"));

do{
	if (isset($okey_x)){								// Is there data to process?
		$db = connect_to_db();
		if (!$db){ $errors[]="can't connect to sql server"; break;}

		if ($sess->is_registered('auth')) $sess->unregister('auth');

		if ($config->clear_text_pw) {
			$q="select phplib_id from ". $config->table_subscriber.
				" where username='$uname' and password='$passw' and domain='$config->realm'";
		} else {
			$ha1=md5($uname.":".$config->realm.":".$passw);
			$q="select phplib_id from ". $config->table_subscriber.
				" where username='$uname' and domain='$config->realm' and ha1='$ha1'";
		}
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		if (!MySQL_Num_Rows($res)) {$errors[]="Bad username or password"; break;}
		$row=MySQL_Fetch_Object($res);

		$sess->register('pre_uid');
		$pre_uid=$row->phplib_id;

        Header("Location: ".$sess->url("my_account.php?kvrk=".uniqID("")));
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
							 "extrahtml"=>"autocomplete='off' style='width:250px;'"));
$f->add_element(array("type"=>"text",
                             "name"=>"passw",
                             "value"=>"",
							 "size"=>20,
							 "maxlength"=>25,
							 "pass"=>1,
							 "extrahtml"=>"style='width:250px;'"));
$f->add_element(array("type"=>"submit",
                             "name"=>"okey",
                             "src"=>$config->img_src_path."butons/b_login.gif",
							 "extrahtml"=>"alt='login'"));


if (isset($_POST['okey_x'])){							//data isn't valid or error in sql
	$passw="";
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();
unset ($page_attributes['tab_collection']);
print_html_body_begin($page_attributes);
?>

<div class="swLPTitle">
<h1><?echo $config->realm;?> Userlogin</h1>
Please enter your username and password:
</div>

<div class="swForm swLoginForm">
<?$f->start("form");				// Start displaying form?>
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td><label for="uname">Username:</label></td>
<td><?$f->show_element("uname");?></td>
</tr>
<tr>
<td><label for="passw">Password:</label></td>
<td><?$f->show_element("passw");?></td>
</tr>
<tr>
<td>&nbsp;</td>
<td align=right><?$f->show_element("okey");?></td>
</tr>
</table>
<?$f->finish();					// Finish form?>
</div>

<div class="swLPSubscLinks">
	<span class="swLPForgotPass"><a href="<?$sess->purl("reg/get_pass.php");?>">Forgot Password?</a></span>
	<span class="swLPSubscribe"><a href="<?$sess->purl("reg/index.php");?>">Subscribe!</a></span>
</div>


<?print_html_body_end();?>
<script language="JavaScript">
<!--
  if (document.forms[0][0].value != '') {
      document.forms[0][1].focus();
  } else {
      document.forms[0][0].focus();
  }
// -->
</script>
</html>
<?page_close();?>

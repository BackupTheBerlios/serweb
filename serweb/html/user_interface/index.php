<?
/*
 * $Id: index.php,v 1.15 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session"));

do{
	if (isset($okey_x)){								// Is there data to process?
		if (!$data = CData_Layer::create($errors)) break;

		if ($sess->is_registered('auth')) $sess->unregister('auth');

		if (false === $phplib_id = $data->check_passw_of_user($_POST['uname'], $config->domain, $_POST['passw'], $errors)) break;
		
		$sess->register('pre_uid');
		$pre_uid=$phplib_id;

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

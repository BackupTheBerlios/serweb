<?
/*
 * $Id: acl.php,v 1.12 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

$f = new form;                   // create a form object
$grp_val=array();
$ACL_control=array();

do{
	if (!$data = CData_Layer::create($errors)) break;

	if (!isset($user_id)) {$errors[]="unknown user"; break;}

	/* get admin ACL control privileges */
	if (false === $ACL_control = $data->get_admin_ACL_privileges($auth->auth["uname"], $config->domain, $errors)) break;

	/* get access control list of user */
	if (false === $grp_val = $data->get_ACL_of_user($user_id, $config->domain, $errors)) break;

	/* add form elements */
	foreach ($ACL_control as $row){
		$f->add_element(array("type"=>"checkbox",
		                      "name"=>"chk_".$row,
		                      "checked"=>in_array($row, $grp_val)?"1":"0",
		                      "value"=>"1"));

		$f->add_element(array("type"=>"hidden",
		                      "name"=>"hidden_".$row,
		                      "value"=>in_array($row, $grp_val)?"1":"0"));
	}

	$f->add_element(array("type"=>"hidden",
	                             "name"=>"user_id",
	                             "value"=>$user_id));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));

	if (isset($_POST['okey_x'])){					// Is there data to process?

		foreach ($ACL_control as $row){
			//if checkbox isn't checked, assign value "0" to variable
			if (!isset($_POST["chk_".$row])) $_POST["chk_".$row] = "0";

			//if state of checkbox was changed
			if ($_POST["chk_".$row] != $_POST["hidden_".$row]){
				if (!$data->update_ACL_of_user($user_id, $config->domain, $row, $_POST["chk_".$row]?'set':'del', $errors)) break;
			}
		}

		if (isset($errors) and $errors) break;

        Header("Location: ".$sess->url("users.php?kvrk=".uniqID("")."&message=".RawURLencode("values changed successfully")));
		page_close();
		exit;
	}
}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();
$page_attributes['selected_tab']="users.php";
print_html_body_begin($page_attributes);
?>

<h2 class="swTitle">Access control list of user: <?echo $user_id;?></h2>

<?if (is_array($ACL_control) and count($ACL_control)){?>
<div class="swForm">
<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
<?	foreach ($ACL_control as $row){ ?>
	<tr>
	<td><label for="<?echo "chk_".$row;?>"><?echo $row;?></label></td>
	<td><?$f->show_element("chk_".$row);?></td>
	</tr>
<?	} ?>
	<tr>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish();					// Finish form?>
</div>
<?}else{?>
<div class="swNumOfFoundRecords">You haven't any privileges to control ACL</div>
<?}?>

<div class="swBackToMainPage"><a href="<?$sess->purl("users.php?kvrk=".uniqid(''));?>">back to main page</a></div>

<?print_html_body_end();?>
</html>
<?page_close();?>

<?
/*
 * $Id: acl.php,v 1.11 2004/04/04 19:42:14 kozlik Exp $
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
	if (!$db = connect_to_db($errors)) break;

	if (!isset($user_id)) {$errors[]="unknown user"; break;}

	/* get admin ACL control privileges */
	$q="select priv_value from ".$config->table_admin_privileges.
		" where domain='".$config->realm."' and username='".$auth->auth["uname"]."'".
				" and priv_name='acl_control'";
	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); break;}

	while ($row = $res->fetchRow(DB_FETCHMODE_OBJECT)) $ACL_control[]=$row->priv_value;
	$res->free();

	/* get access control list of user */
	$q="select grp from ".$config->table_grp." where domain='".$config->realm."' and username='".$user_id."'";
	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); break;}

	while ($row = $res->fetchRow(DB_FETCHMODE_OBJECT)) $grp_val[]=$row->grp;
	$res->free();

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
				if ($_POST["chk_".$row])
					$q="insert into ".$config->table_grp." (username, domain, grp, last_modified) ".
						"values ('".$user_id."', '".$config->realm."', '".$row."', now())";
				else
					$q="delete from ".$config->table_grp." where ".
						"domain='".$config->realm."' and username='".$user_id."' and grp='".$row."'";

				$res=$db->query($q);
				if (DB::isError($res)) {log_errors($res, $errors); break;}
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

<?if (count($ACL_control)){?>
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

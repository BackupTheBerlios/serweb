<?
/*
 * $Id: acl.php,v 1.7 2004/03/04 22:47:37 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

$f = new form;                   // create a form object
$grp_val=array();
$ACL_control=array();

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="cannot connect to sql server"; break;}

	if (!isset($user_id)) {$errors[]="unknown user"; break;}

	/* get admin ACL control privileges */
	$q="select priv_value from ".$config->table_admin_privileges.
		" where domain='".$config->realm."' and username='".$auth->auth["uname"]."'".
				" and priv_name='acl_control'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

	while ($row=MySQL_Fetch_Object($res)) $ACL_control[]=$row->priv_value;
	
	/* get access control list of user */
	$q="select grp from ".$config->table_grp." where domain='".$config->realm."' and username='".$user_id."'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

	while ($row=MySQL_Fetch_Object($res)) $grp_val[]=$row->grp;

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

	if (isset($okey_x)){								// Is there data to process?

		foreach ($ACL_control as $row){
			//if checkbox isn't checked, assign value "0" to variable
			if ($_POST["chk_".$row] != "1") $_POST["chk_".$row] != "0";

			//if state of checkbox was changed
			if ($_POST["chk_".$row] != $_POST["hidden_".$row]){
				if ($_POST["chk_".$row])
					$q="insert into ".$config->table_grp." (username, domain, grp, last_modified) ".
						"values ('".$user_id."', '".$config->realm."', '".$row."', now())";
				else
					$q="delete from ".$config->table_grp." where ".
						"domain='".$config->realm."' and username='".$user_id."' and grp='".$row."'";

				$res=MySQL_Query($q);
				if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
			}
		}

		if ($errors) break;

        Header("Location: ".$sess->url("users.php?kvrk=".uniqID("")."&message=".RawURLencode("values changed successfully")));
		page_close();
		exit;
	}
}while (false);

if ($okey_x){							//data isn't valid or error in sql
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
	print_admin_html_body_begin("users.php");
	echo "<br>";
//	echo "user: ".$user_id."<br>";
	print_errors($errors);                    // Display error
	print_message($message);

	ptitle("Access control list of user: ".$user_id);
?>


<?if (count($ACL_control)){?>
<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
<?	foreach ($ACL_control as $row){ ?>
	<tr>
	<td align="right" class="f12b"><?echo $row;?></td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("chk_".$row);?></td>
	</tr>
<?	} ?>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish();					// Finish form?>
<?}else{?>
<br><div align="center">You haven't any privileges to control ACL</div>
<?}?>
<br clear="all"><br>

<a href="<?$sess->purl("users.php?kvrk=".uniqid(''));?>">back to main page</a>

<?print_html_body_end();?>
</html>
<?page_close();?>

<?
/*
 * $Id: admin_privileges.php,v 1.1 2004/03/04 22:47:37 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin,change_priv");

$f = new form;                   // create a form object
$ad_priv=array();
$ad_priv['acl_control']=array();

if (isset($_GET['user_domain'])) $user_domain=$_GET['user_domain'];
elseif (isset($_POST['user_domain'])) $user_domain=$_POST['user_domain'];

if (isset($_GET['user_id'])) $user_id=$_GET['user_id'];
elseif (isset($_POST['user_id'])) $user_id=$_POST['user_id'];

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="cannot connect to sql server"; break;}

	if (!isset($user_id)) {$errors[]="unknown user"; break;}

	/* get access control list of user */
	$q="select priv_name, priv_value from ".$config->table_admin_privileges." where domain='".$user_domain."' and username='".$user_id."'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

	while ($row=MySQL_Fetch_Object($res)) $ad_priv[$row->priv_name][]=$row->priv_value;

	/* add form elements */
	foreach ($config->grp_values as $row){
		$f->add_element(array("type"=>"checkbox",
		                      "name"=>"chk_".$row,
		                      "checked"=>in_array($row, $ad_priv['acl_control'])?"1":"0",
		                      "value"=>"1"));

		$f->add_element(array("type"=>"hidden",
		                      "name"=>"hidden_".$row,
		                      "value"=>in_array($row, $ad_priv['acl_control'])?"1":"0"));
	}

	$f->add_element(array("type"=>"checkbox",
	                      "name"=>"chk_change_privileges",
	                      "checked"=>$ad_priv['change_privileges'][0]?"1":"0",
	                      "value"=>"1"));

	$f->add_element(array("type"=>"hidden",
	                      "name"=>"hidden_change_privileges",
	                      "value"=>$ad_priv['change_privileges'][0]?"1":"0"));
	
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"user_id",
	                             "value"=>$user_id));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"user_domain",
	                             "value"=>$user_domain));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));

	if (isset($okey_x)){								// Is there data to process?

		foreach ($config->grp_values as $row){
			//if checkbox isn't checked, assign value "0" to variable
			if ($_POST["chk_".$row] != "1") $_POST["chk_".$row] != "0";

			//if state of checkbox was changed
			if ($_POST["chk_".$row] != $_POST["hidden_".$row]){
				if ($_POST["chk_".$row])
					$q="insert into ".$config->table_admin_privileges." (username, domain, priv_name, priv_value) ".
						"values ('".$user_id."', '".$user_domain."', 'acl_control', '".$row."')";
				else
					$q="delete from ".$config->table_admin_privileges." where ".
						"domain='".$user_domain."' and username='".$user_id."' and priv_name='acl_control' and priv_value='".$row."'";

				$res=MySQL_Query($q);
				if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
			}
		}
		if ($errors) break;

		if ($_POST["chk_change_privileges"] != "1") $_POST["chk_change_privileges"] != "0";
		if ($_POST["chk_change_privileges"] != $_POST["hidden_change_privileges"]){
			if ($_POST["chk_change_privileges"])
				
				if (isset($ad_priv['change_privileges'])){ /* if privilege is in db we must update its value */
					$q="update ".$config->table_admin_privileges." set priv_value='1' ".
						"where domain='".$user_domain."' and username='".$user_id."' and priv_name='change_privileges'";
				} else { /* otherwise we insert privilege with right value */
					$q="insert into ".$config->table_admin_privileges." (username, domain, priv_name, priv_value) ".
						"values ('".$user_id."', '".$user_domain."', 'change_privileges', '1')";
				}
			else
				$q="delete from ".$config->table_admin_privileges." where ".
					"domain='".$user_domain."' and username='".$user_id."' and priv_name='change_privileges'";

			$res=MySQL_Query($q);
			if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		}
		
        Header("Location: ".$sess->url("list_of_admins.php?kvrk=".uniqID("")."&message=".RawURLencode("values changed successfully")));
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
	print_admin_html_body_begin("list_of_admins.php");
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);

	ptitle("Admin privileges of ".$user_id);
?>


<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td colspan="3" align="center" class="f12i">ACL control</td>
	</tr>
<?	foreach ($config->grp_values as $row){ ?>
	<tr>
	<td align="right" class="f12b"><?echo $row;?></td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("chk_".$row);?></td>
	</tr>
<?	} ?>
	<tr>
	<tr>
	<td colspan="3">&nbsp;</td>
	</tr>
	<td align="right" class="f12b">changes privileges of admins</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("chk_change_privileges");?></td>
	</tr>
	<tr>
	<td align="center" colspan="3"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish();					// Finish form?>
<br clear="all"><br>

<a href="<?$sess->purl("list_of_admins.php?kvrk=".uniqid(''));?>">back to main page</a>

<?print_html_body_end();?>
</html>
<?page_close();?>

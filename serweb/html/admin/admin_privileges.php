<?
/*
 * $Id: admin_privileges.php,v 1.6 2004/04/05 19:31:03 kozlik Exp $
 */

require "prepend.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin,change_priv");

$f = new form;                   // create a form object
$ad_priv=array();
$ad_priv['acl_control']=array();
$ad_priv['change_privileges']=array();
$ad_priv['is_admin']=array();

if (isset($_GET['user_domain'])) $user_domain=$_GET['user_domain'];
elseif (isset($_POST['user_domain'])) $user_domain=$_POST['user_domain'];

if (isset($_GET['user_id'])) $user_id=$_GET['user_id'];
elseif (isset($_POST['user_id'])) $user_id=$_POST['user_id'];


/*
	update privilege in DB
	$priv_name - name of privilege
	$priv_type - associative array
		$priv_type['type'] - type of privilege can be 'boolean' or 'multivalue'
		$priv_type['values'] - for 'multivalue' type used to store array of potential values
	$user_id	 - username of user which atributes are updated
	$user_domain - domain of user which atributes are updated
	$db	- DB handler
	$errors - array in which arrors messages are returned
*/

function update_db($priv_name, $priv_type, $user_id, $user_domain, $db, &$errors){
	global $_POST, $ad_priv, $config;

	switch ($priv_type['type']){
	case "boolean":	
		//if checkbox isn't checked, assign value "0" to variable
		if (!isset($_POST["chk_".$priv_name])) $_POST["chk_".$priv_name] = "0";

		if ($_POST["chk_".$priv_name] != $_POST["hidden_".$priv_name]){
			if ($_POST["chk_".$priv_name])

				if (isset($ad_priv[$priv_name][0])){ /* if privilege is in db we must update its value */
					$q="update ".$config->table_admin_privileges." set priv_value='1' ".
						"where domain='".$user_domain."' and username='".$user_id."' and priv_name='".$priv_name."'";
				} else { /* otherwise we insert privilege with right value */
					$q="insert into ".$config->table_admin_privileges." (username, domain, priv_name, priv_value) ".
						"values ('".$user_id."', '".$user_domain."', '".$priv_name."', '1')";
				}
			else
				$q="delete from ".$config->table_admin_privileges." where ".
					"domain='".$user_domain."' and username='".$user_id."' and priv_name='".$priv_name."'";

			$res=$db->query($q);
			if (DB::isError($res)) {log_errors($res, $errors); return false;}
		}
		break;

	case "multivalue":
		foreach ($priv_type['values'] as $row){
			//if checkbox isn't checked, assign value "0" to variable
			if (!isset($_POST["chk_".$row])) $_POST["chk_".$row] = "0";

			//if state of checkbox was changed
			if ($_POST["chk_".$row] != $_POST["hidden_".$row]){
				if ($_POST["chk_".$row])
					$q="insert into ".$config->table_admin_privileges." (username, domain, priv_name, priv_value) ".
						"values ('".$user_id."', '".$user_domain."', '".$priv_name."', '".$row."')";
				else
					$q="delete from ".$config->table_admin_privileges." where ".
						"domain='".$user_domain."' and username='".$user_id."' and priv_name='".$priv_name."' and priv_value='".$row."'";

				$res=$db->query($q);
				if (DB::isError($res)) {log_errors($res, $errors); return false;}
			}
		}
		break;
	default:
		$errors[]="non existent priv type"; return false;
	}//end switch
	
	return true;
} //end function update_db


do{
	if (!$db = connect_to_db($errors)) break;

	if (!isset($user_id)) {$errors[]="unknown user"; break;}

	/* get access control list of user */
	$q="select priv_name, priv_value from ".$config->table_admin_privileges." where domain='".$user_domain."' and username='".$user_id."'";
	$res=$db->query($q);
	if (DB::isError($res)) {log_errors($res, $errors); break;}

	while ($row = $res->fetchRow(DB_FETCHMODE_OBJECT)) $ad_priv[$row->priv_name][]=$row->priv_value;
	$res->free();

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
	                      "checked"=>isset($ad_priv['change_privileges'][0]) and $ad_priv['change_privileges'][0]?"1":"0",
	                      "value"=>"1"));

	$f->add_element(array("type"=>"hidden",
	                      "name"=>"hidden_change_privileges",
	                      "value"=>isset($ad_priv['change_privileges'][0]) and $ad_priv['change_privileges'][0]?"1":"0"));

	$f->add_element(array("type"=>"checkbox",
	                      "name"=>"chk_is_admin",
	                      "checked"=>isset($ad_priv['is_admin'][0]) and $ad_priv['is_admin'][0]?"1":"0",
	                      "value"=>"1",
						  "extrahtml"=>"onclick='disable_chk(this);'"));

	$f->add_element(array("type"=>"hidden",
	                      "name"=>"hidden_is_admin",
	                      "value"=>isset($ad_priv['is_admin'][0]) and $ad_priv['is_admin'][0]?"1":"0"));

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

	if (isset($_POST['okey_x'])){					// Is there data to process?

		if (!update_db('is_admin', array('type'=>'boolean'), $user_id, $user_domain, $db, $errors)) break;
		if (!update_db('change_privileges', array('type'=>'boolean'), $user_id, $user_domain, $db, $errors)) break;
		if (!update_db('acl_control', array('type'=>'multivalue', 'values'=>$config->grp_values), $user_id, $user_domain, $db, $errors)) break;
		
        Header("Location: ".$sess->url("list_of_admins.php?kvrk=".uniqID("")."&message=".RawURLencode("values changed successfully")));
		page_close();
		exit;
	}
}while (false);

if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>
<script language="JavaScript">
<!--
	/* disable other checkboxes if is_admin checkbox is not checked */

	function disable_chk(is_admin){
		f=is_admin.form;

		for (i=0; i<f.elements.length; i++){
			el=f.elements[i];
			if (el.type=="checkbox" && el!=is_admin) {
				if (is_admin.checked) el.disabled=false;
				else el.disabled=true;
			}
		}
	}
//-->
</script>
<?
$page_attributes['selected_tab']="list_of_admins.php";
print_html_body_begin($page_attributes);
?>

<h2 class="swTitle">Admin privileges of <?echo $user_id;?></h2>

<div class="swForm">
<?$f->start("form", "", "", "", "form1");				// Start displaying form?>

	<div class="swFieldset">
	<fieldset class="swWidthAsTitle">
	<legend>admin competence</legend>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="chk_is_admin">is admin</label></td>
	<td><?$f->show_element("chk_is_admin");?></td>
	</tr>
	<tr>
	<td><label for="chk_change_privileges">changes privileges of admins</label></td>
	<td><?$f->show_element("chk_change_privileges");?></td>
	</tr>
	</table>
	</fieldset>
	</div>

	<div class="swFieldset">
	<fieldset class="swWidthAsTitle">
	<legend>ACL control</legend>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
<?	foreach ($config->grp_values as $row){ ?>
	<tr>
	<td><label for="<?echo "chk_".$row;?>"><?echo $row;?></label></td>
	<td><?$f->show_element("chk_".$row);?></td>
	</tr>
<?	} ?>
	</table>
	</fieldset>
	</div>

	<br />
	<div align="center"><?$f->show_element("okey");?></div>

<?$f->finish();					// Finish form?>
</div>

<div class="swBackToMainPage"><a href="<?$sess->purl("list_of_admins.php?kvrk=".uniqid(''));?>">back to main page</a></div>

<br>
<?print_html_body_end();?>
<script language="JavaScript">
<!--
	/* disable other checkboxes if is_admin checkbox is not checked */

	disable_chk(document.form1.chk_is_admin);
	
//-->
</script>
</html>
<?page_close();?>

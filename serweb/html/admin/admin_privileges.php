<?
/*
 * $Id: admin_privileges.php,v 1.10 2004/11/10 13:13:06 kozlik Exp $
 */

$_data_layer_required_methods=array('add_privilege_to_user', 'del_privilege_of_user', 'get_privileges_of_user');

$_phplib_page_open = array("sess" => "phplib_Session",
						   "auth" => "phplib_Pre_Auth",
						   "perm" => "phplib_Perm");

require "prepend.php";

$perm->check("admin,change_priv");

$f = new form;                   // create a form object
$ad_priv=array();
$ad_priv['acl_control']=array();
$ad_priv['change_privileges']=array();
$ad_priv['is_admin']=array();
$errors = array();


$uid = get_userauth_from_get_param('u');


/*
	update privilege in DB
	$priv_name - name of privilege
	$priv_type - associative array
		$priv_type['type'] - type of privilege can be 'boolean' or 'multivalue'
		$priv_type['values'] - for 'multivalue' type used to store array of potential values
	$user_id	 - user which atributes are updated
	$data	- DB handler
	$errors - array in which arrors messages are returned
*/

function update_db($priv_name, $priv_type, $user_id, $data, &$errors){
	global $_POST, $ad_priv, $config;

	switch ($priv_type['type']){
	case "boolean":
		//if checkbox isn't checked, assign value "0" to variable
		if (!isset($_POST["chk_".$priv_name])) $_POST["chk_".$priv_name] = "0";

		if ($_POST["chk_".$priv_name] != $_POST["hidden_".$priv_name]){
			if ($_POST["chk_".$priv_name]){
				if (!$data->add_privilege_to_user($user_id, $priv_name, '1', isset($ad_priv[$priv_name][0]), $errors)) return false;
			}
			else{
				if (!$data->del_privilege_of_user($user_id, $priv_name, NULL, $errors)) return false;
			}
		}
		break;

	case "multivalue":
		foreach ($priv_type['values'] as $row){
			//if checkbox isn't checked, assign value "0" to variable
			if (!isset($_POST["chk_".$row])) $_POST["chk_".$row] = "0";

			//if state of checkbox was changed
			if ($_POST["chk_".$row] != $_POST["hidden_".$row]){
				if ($_POST["chk_".$row]){
					if (!$data->add_privilege_to_user($user_id, $priv_name, $row, false, $errors)) return false;
				}
				else{
					if (!$data->del_privilege_of_user($user_id, $priv_name, $row, $errors)) return false;
				}
			}
		}
		break;
	default:
		$errors[]="non existent priv type"; return false;
	}//end switch

	return true;
} //end function update_db


do{
	if (!isset($uid)) {$errors[]="unknown user"; break;}

	/* get privileges of user */
	if (false === $privs = $data->get_privileges_of_user($uid, NULL, $errors)) break;
	foreach($privs as $row)	$ad_priv[$row->priv_name][]=$row->priv_value;

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

	userauth_to_form($uid, 'u', $f);

	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));

	if (isset($_POST['okey_x'])){					// Is there data to process?

		if (!update_db('is_admin', array('type'=>'boolean'), $uid, $data, $errors)) break;
		if (!update_db('change_privileges', array('type'=>'boolean'), $uid, $data, $errors)) break;
		if (!update_db('acl_control', array('type'=>'multivalue', 'values'=>$config->grp_values), $uid, $data, $errors)) break;

        Header("Location: ".$sess->url("list_of_admins.php?kvrk=".uniqID("")."&m_priv_saved=1"));
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

$page_attributes['errors']=&$errors;
$page_attributes['message']=&$message;

$smarty->assign_by_ref('parameters', $page_attributes);
$smarty->assign('grp_values', $config->grp_values);

$smarty->assign_phplib_form('form', $f,
						array('jvs_name'=>'form',
						      'form_name'=>'form1'));

$smarty->assign('uname', $uid->uname);

$smarty->assign_by_ref('lang_str', $lang_str);

$smarty->display('a_admin_privileges.tpl');

?>
<?print_html_body_end();?>
<script language="JavaScript">
<!--
	/* disable other checkboxes if is_admin checkbox is not checked */

	disable_chk(document.form1.chk_is_admin);

//-->
</script>
</html>
<?page_close();?>

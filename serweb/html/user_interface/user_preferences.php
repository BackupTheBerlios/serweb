<?
/*
 * $Id: user_preferences.php,v 1.7 2004/04/04 19:42:14 kozlik Exp $
 */

require "prepend.php";
require "../user_preferences.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object
$usr_pref = new User_Preferences();

class Cattrib{
	var $att_name;
	var $att_value;
	var $att_rich_type;
	var $att_type_spec;

	function Cattrib($att_name, $att_value, $att_rich_type, $att_type_spec){
		$this->att_name=$att_name;
		$this->att_value=$att_value;
		$this->att_rich_type=$att_rich_type;
		$this->att_type_spec=$att_type_spec;
	}
}


do{
	if (!$db = connect_to_db($errors)) break;

	$attributes=array();

	//get list of attributes
	$q="select att_name, att_rich_type, att_type_spec, default_value from ".$config->table_user_preferences_types.
		" order by att_name";
	$att_res=$db->query($q);
	if (DB::isError($att_res)) {log_errors($att_res, $errors); break;}

	while ($row=$att_res->fetchRow(DB_FETCHMODE_OBJECT)){
		$attributes[$row->att_name]=new Cattrib($row->att_name, $row->default_value, $row->att_rich_type, $row->att_type_spec);
	}
	$att_res->free();

	// get attributes values
	$q="select attribute, value from ".$config->table_user_preferences.
		" where domain='".$config->realm."' and username='".$auth->auth["uname"]."'";
	$att_res=$db->query($q);
	if (DB::isError($att_res)) {log_errors($att_res, $errors); break;}

	while ($row=$att_res->fetchRow(DB_FETCHMODE_OBJECT)){
		$attributes[$row->attribute]->att_value = $row->value;
	}
	$att_res->free();

	// add elements to form object

	foreach($attributes as $att){
		$usr_pref->form_element($f, $att->att_name, $att->att_value, $att->att_rich_type, $att->att_type_spec);
	}

	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));

	if (isset($_POST['okey_x'])){								// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			// Process data            // Data ok;

		//check values of attributes and format its
		foreach($attributes as $att){
			if (!$usr_pref->format_inputed_value($HTTP_POST_VARS[$att->att_name], $att->att_rich_type, $att->att_type_spec)){
				$errors[]="invalid value of attribute ".$att->att_name; break;
			}

			//if att value is changed
			if ($HTTP_POST_VARS[$att->att_name] != $HTTP_POST_VARS["_hidden_".$att->att_name]){
				//insert into DB
				$q="replace into ".$config->table_user_preferences." (username, domain, attribute, value) ".
					"values ('".$auth->auth["uname"]."', '".$config->realm."', '".$att->att_name."', '".$HTTP_POST_VARS[$att->att_name]."')";

				$res=$db->query($q);
				if (DB::isError($res)) {log_errors($res, $errors); break;}
			}
		}

		if (isset($errors) and $errors) break;

        Header("Location: ".$sess->url("user_preferences.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

}while (false);


if (isset($_POST['okey_x'])){			//data isn't valid or error in sql
	$f->load_defaults();				// Load form with submitted data
}

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>
<script language="JavaScript" src="<?echo $config->js_src_path;?>sip_address_completion.js.php"></script>

<?
$page_attributes['user_name']=get_user_name($db, $errors);
print_html_body_begin($page_attributes);
?>

<div class="swForm">
<?if (count($attributes)){
	$js_on_subm="";
  $f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<?foreach($attributes as $att){
		if ($att->att_rich_type == "sip_adr") $js_on_subm.="sip_address_completion(f.".$att->att_name.");";
	?>
		<tr>
		<td align="right" class="f12b"><label for="<?echo $att->att_name;?>"><?echo $att->att_name;?>:</label></td>
		<td><?$f->show_element($att->att_name);?></td>
		</tr>
	<?}//foreach?>
	<tr>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("",$js_on_subm);					// Finish form?>
</div>

<?}else{?>
<div class="swNumOfFoundRecords">No attributes defined by admin</div>
<?} //end if (count($attributes))?>


<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

<?
/*
 * $Id: user_preferences.php,v 1.8 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";
require "../user_preferences.php";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object
$usr_pref = new User_Preferences();


do{
	if (!$data = CData_Layer::create($errors)) break;

	$attributes=array();

	//get list of attributes
	if (false === $attributes = $data->get_attributes(NULL, $errors)) break;
	
	// get attributes values
	if (false === $data->get_att_values($auth->auth["uname"], $config->domain, $attributes, $errors)) break;

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
				if (false === $data->update_attribute_of_user($auth->auth["uname"], $config->domain, $att->att_name, $HTTP_POST_VARS[$att->att_name], $errors)) break;
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
$page_attributes['user_name']=$data->get_user_name($errors);
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

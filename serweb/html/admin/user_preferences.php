<?
/*
 * $Id: user_preferences.php,v 1.1 2004/02/24 08:53:08 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";
require "../user_preferences.php";

$usr_pref = new User_Preferences();

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="cannot connect to sql server"; break;}

	//delete attrib from DB
	if ($att_dele){
		//delete attribute from user_preferences table
		$q="delete from ".$config->table_user_preferences.
			" where attribute='$att_dele'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		//delete attribute form user_preferences_types table
		$q="delete from ".$config->table_user_preferences_types.
			" where att_name='$att_dele'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

        Header("Location: ".$sess->url("user_preferences.php?kvrk=".uniqID("")));
		page_close();
		exit;
	}

	$att_type_spec=null;
	
	//select values of edited attribute in order to fill its to the form
	if ($att_edit){
		$q="select att_name, att_type, att_type_spec, default_value from ".$config->table_user_preferences_types.
			" where att_name='$att_edit'";
		$res=mySQL_query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		$row=mysql_fetch_object($res);
		
		$att_type_spec=$row->att_type_spec;
		
		//if $att_type is not set by http_post (form not submited yet) set it by value in DB
		if (!isset($att_type)) $att_type=$row->att_type;
	}

	//create array of options of select
	$opt=array();
	foreach($usr_pref->att_types as $k => $v){
		$opt[]=array("label" => $v, "value" => $k);
	}
	
	$f = new form;                   // create a form object

	$f->add_element(array("type"=>"text",
	                             "name"=>"att_name",
	                             "value"=>$row->att_name?$row->att_name:"",
								 "size"=>16,
								 "maxlength"=>32,
								 "minlength"=>1,
								 "length_e"=>"you must fill attribute name",
//	                             "valid_regex"=>"^[a-zA-Z_][a-zA-Z0-9_]*$",
//	                             "valid_e"=>"in attribut name use only charakters 'A-Z', '0-9' and '_'",
								 "extrahtml"=>"style='width:120px;'"));

	$f->add_element(array("type"=>"select",
    	                         "name"=>"att_type",
	                             "value"=>$row->att_type?$row->att_type:"",
								 "size"=>1,
								 "options"=>$opt,
								 "extrahtml"=>"style='width:120px;'"));

	$f->add_element(array("type"=>"text",
    	                         "name"=>"default_value",
	                             "value"=>isset($row->default_value)?$row->default_value:"",
								 "size"=>16,
								 "maxlength"=>255,
								 "extrahtml"=>"style='width:120px;'"));

	$f->add_element(array("type"=>"hidden",
	                             "name"=>"att_edit",
	                             "value"=>$att_edit?$att_edit:""));

	$f->add_element(array("type"=>"submit",
    	                         "name"=>"okey",
        	                     "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));

								 
	if (isset($okey_x)){								// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}
		
		//check and format default value of attribute
		if (!$usr_pref->format_inputed_value($default_value, $att_type, $att_type_spec)){
			$errors[]="bad default value"; break;
		}

			/* Process data */           // Data ok;

		if ($att_edit) 
			$q="update ".$config->table_user_preferences_types." ".
				"set att_name='$att_name', att_type='$att_type', default_value='$default_value' ".
				"where att_name='$att_edit'";
		else 
			$q="insert into ".$config->table_user_preferences_types." (att_name, att_type, default_value) ".
				"values ('$att_name', '$att_type', '$default_value')";

		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		//if name of attribute is changed, update user_preferences table
		if ($att_edit and $att_edit!=$att_name){
			$q="update ".$config->table_user_preferences." ".
				"set attribute='$att_name' where attribute='$att_edit'";

			$res=MySQL_Query($q);
			if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		}

		if (!$att_edit and $att_type=="list") 
	        Header("Location: ".$sess->url("edit_list_items.php?attrib_name=".RawURLEncode($att_name)."&kvrk=".uniqID("")));
		else
	        Header("Location: ".$sess->url("user_preferences.php?kvrk=".uniqID("")));

		page_close();
		exit;
	}
}while (false);
								 
do{
	if ($db){
		// get attrib table
		if ($att_edit) $qw=" att_name != '$att_edit' "; else $qw="1";

		$q="select att_name, att_type, att_type_spec, default_value from ".$config->table_user_preferences_types.
			" where ".$qw." order by att_name";
		$att_res=MySQL_Query($q);
		if (!$att_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
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
	print_admin_html_body_begin();
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);

?>

<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="right" class="f12b">attribute name:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("att_name");?></td>
	</tr>
	<tr>
	<td align="right" class="f12b">attribute type:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("att_type");?></td>
	</tr>
	<tr>
	<td align="right" class="f12b">default value:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("default_value");?></td>
	</tr>
	<tr>
	<td align="left">&nbsp;<?
		if($att_edit and $att_type=="list"){?>
			<a href="<?$sess->purl("edit_list_items.php?attrib_name=".RawURLEncode($att_edit)."&kvrk=".uniqID(""))?>"><img src="<?echo $config->img_src_path;?>butons/b_edit_items_of_the_list.gif" width="165" height="16" border="0"></a><?
		}?></td>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","");					// Finish form?>


<?if (MySQL_num_rows($att_res)){?>

<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT" width="205">attribute name</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="205">attribute type</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="205">default value</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="50">&nbsp;</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="50">&nbsp;</td>
	</tr>
	<tr><td colspan="9" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?$odd=0;
	while($row = MySQL_Fetch_Object($att_res)){
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'bgcolor="#FFFFFF"':'bgcolor="#EAF0F4"';?>>
	<td align="left" class="f12" width="205">&nbsp;<?echo $row->att_name;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12" width="205">&nbsp;<?echo $usr_pref->att_types[$row->att_type];?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12" width="205">&nbsp;<?echo $usr_pref->format_value_for_output($row->default_value, $row->att_type, $row->att_type_spec);?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="50"><a href="<?$sess->purl("user_preferences.php?kvrk=".uniqID("")."&att_edit=".RawURLEncode($row->att_name));?>">edit</a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="50"><a href="<?$sess->purl("user_preferences.php?kvrk=".uniqID("")."&att_dele=".RawURLEncode($row->att_name));?>">delete</a></td>
	</tr>
	<?}?>
	</table>
</td></tr>
</table>

<?}?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

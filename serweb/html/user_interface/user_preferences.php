<?
/*
 * $Id: user_preferences.php,v 1.3 2004/03/02 21:07:41 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";
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
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}

	$attributes=array();
	
	//get list of attributes
	$q="select att_name, att_rich_type, att_type_spec, default_value from ".$config->table_user_preferences_types.
		" order by att_name";
	$att_res=MySQL_Query($q);
	if (!$att_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

	while ($row=MySQL_Fetch_Object($att_res)){
		$attributes[$row->att_name]=new Cattrib($row->att_name, $row->default_value, $row->att_rich_type, $row->att_type_spec);
	}

	// get attributes values
	$q="select attribute, value from ".$config->table_user_preferences.
		" where domain='".$config->realm."' and username='".$auth->auth["uname"]."'";
	$att_res=MySQL_Query($q);
	if (!$att_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

	while ($row=MySQL_Fetch_Object($att_res)){
		$attributes[$row->attribute]->att_value = $row->value;
	}

	// add elements to form object
		
	foreach($attributes as $att){
		$usr_pref->form_element($f, $att->att_name, $att->att_value, $att->att_rich_type, $att->att_type_spec);
	}

	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));
								 
	if (isset($okey_x)){								// Is there data to process?
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
	
				$res=MySQL_Query($q);
				if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
			}
		}
		
		if ($errors) break;		

        Header("Location: ".$sess->url("user_preferences.php?kvrk=".uniqID("")));
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
<script language="JavaScript">
<!--
	function sip_address_completion(adr){
		var default_domain='<?echo $config->default_domain;?>';

		var re = /^<?echo str_replace('/','\/',$reg->user);?>$/i;
		if (re.test(adr.value)) {
			adr.value=adr.value+'@'+default_domain;
		}

		var re = /^<?echo str_replace('/','\/',$reg->address);?>$/i
		var re2= /^sip:/i;
		if (re.test(adr.value) && !re2.test(adr.value)) {
			adr.value='sip:'+adr.value;
		}
	}
//-->
</script>
<script language="JavaScript" src="ctd.js"></script>
</head>
<?
	print_html_body_begin(11, true, true, get_user_name($errors));
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>

<?if (count($attributes)){
	$js_on_subm="";
  $f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<?foreach($attributes as $att){
		if ($att->att_rich_type == "sip_adr") $js_on_subm.="sip_address_completion(f.".$att->att_name.");"; 
	?>
		<tr>
		<td align="right" class="f12b"><?echo $att->att_name;?></td>
		<td width="5">&nbsp;</td>
		<td><?$f->show_element($att->att_name);?></td>
		</tr>
	<?}//foreach?>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("",$js_on_subm);					// Finish form?>

<?}else{?>
<br><div align="center">No attributes defined by admin</div>
<?} //end if (count($attributes))?>


<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

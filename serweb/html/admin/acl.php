<?
/*
 * $Id: acl.php,v 1.2 2002/09/10 15:59:35 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

$f = new form;                   // create a form object

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}

	if (!isset($user_id)) {$errors[]="unknown user"; break;}
	
	$q="select grp from ".$config->table_grp." where user='".$user_id."'";
	$res=mySQL_query($q);
	if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	
	while ($row=MySQL_Fetch_Object($res)) $grp_val[]=$row->grp;
	

	unset ($options);
	foreach($config->grp_values as $val){
		$options[]=array("label"=>$val, "value"=>$val);
	}

	
	if (is_array($grp_val)){
		$diff=array_diff($grp_val, $config->grp_values);
		$options=array_merge($options, $diff);
	}
	
	$f->add_element(array("type"=>"select",
	                             "name"=>"f_grp",
								 "size"=>10,
	                             "value"=>$grp_val,
								 "multiple"=>1,
								 "options"=>$options,
								 "extrahtml"=>"style='width:240px;'"));
	$f->add_element(array("type"=>"hidden",
	                             "name"=>"user_id",
	                             "value"=>$user_id));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_save.gif",
								 "extrahtml"=>"alt='save'"));
								 

	if (isset($okey_x)){								// Is there data to process?

		if (!is_array($grp_val)) $grp_val=array();
		if (!is_array($f_grp)) $f_grp=array();
	
		$del=array_diff($grp_val, $f_grp);
		if (is_array($del))
			foreach($del as $val){
				$q="delete from ".$config->table_grp." where user='$user_id' and grp='$val'";
				$res=MySQL_Query($q);
				if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
			}
		

		$ins=array_diff($f_grp, $grp_val);
		if (is_array($ins))
			foreach($ins as $val){
				$q="insert into ".$config->table_grp." (user, grp, last_modified) values ('$user_id', '$val', now())";
				$res=MySQL_Query($q);
				if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
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
<title>iptel.org, the IP Telephony Site</title>
<?print_html_head();?>
</head>
<?
	print_html_body_begin(false, true, true);
	echo "<br>";
	echo "user: ".$user_id."<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>

<?$f->start("form","get");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><?$f->show_element("f_grp");?></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td align="right"><?$f->show_element("okey");?></td></tr>
	</table>
<?$f->finish();					// Finish form?>

<br clear="all"><br>

<a href="<?$sess->purl("users.php?kvrk=".uniqid(''));?>">back to main page</a>

<?print_html_body_end();?>
</html>
<?page_close();?>

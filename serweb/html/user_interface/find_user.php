<?
/*
 * $Id: find_user.php,v 1.4 2003/03/17 18:18:35 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Auth"));

define("max_rows",50);

$reg = new Creg;				// create regular expressions class
$f = new form;                  // create a form object

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}
	
	
	$f->add_element(array("type"=>"text",
	                             "name"=>"fname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"lname",
								 "size"=>16,
								 "maxlength"=>32,
	                             "value"=>"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"text",
	                             "name"=>"uname",
								 "size"=>16,
								 "maxlength"=>50,
	                             "value"=>"",
								 "extrahtml"=>"style='width:120px;'"));
	$f->add_element(array("type"=>"checkbox",
	                             "value"=>1,
	                             "name"=>"onlineonly"));
	$f->add_element(array("type"=>"submit",
	                             "name"=>"okey",
	                             "src"=>$config->img_src_path."butons/b_find.gif",
								 "extrahtml"=>"alt='find'"));
	
	
	if (isset($okey_x)){						// Is there data to process?
		if ($err = $f->validate()) {			// Is the data valid?
			$errors=array_merge($errors, $err); // No!
			break;
		}

			/* Process data */           // Data ok; 
		if ($onlineonly)
			$q=	"select distinct s.timezone, s.first_name, s.last_name, s.user_id from ".$config->table_subscriber." s, ".$config->table_location." l ".
				" where s.user_id=l.user and s.allow_find='1' and s.first_name like '%$fname%' and s.last_name like '%$lname%' and s.user_id like '%$uname%' limit 0,".$config->max_showed_rows;
		else
			$q=	"select timezone, first_name, last_name, user_id from ".$config->table_subscriber.
				" where allow_find='1' and first_name like '%$fname%' and last_name like '%$lname%' and user_id like '%$uname%' limit 0,".$config->max_showed_rows;
		
		$find_res=MySQL_Query($q);
		if (!$find_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	}

}while (false);

if (isset($okey_x)){							//data isn't valid or error in sql
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
	print_html_body_begin(2, true, true, get_user_name($errors));
	echo "<br>";?>
<?	print_errors($errors);                    // Display error
	print_message($message);
?>

<table width="498" border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td class="title">Find user</td></tr>
</table>


<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="right" class="f12b">first name:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("fname");?></td>
	</tr>
	<tr>
	<td align="right" class="f12b">last name:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("lname");?></td>
	</tr>
	<tr>
	<td align="right" class="f12b">user name:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("uname");?></td>
	</tr>
	<tr>
	<td align="right" class="f12b">show on-line users only:</td>
	<td width="5">&nbsp;</td>
	<td><?$f->show_element("onlineonly");?></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align="right"><?$f->show_element("okey");?></td>
	</tr>
	</table>
<?$f->finish("","sip_address_completion(f.sip_uri);");					// Finish form?>

<?if ($find_res and MySQL_num_rows($find_res)){?>

<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT" width="160">name</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="205">sip address</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="125">timezone</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="125">&nbsp;</td>
	</tr>
	<tr><td colspan="11" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?$odd=0;
	while ($row=MySQL_fetch_object($find_res)){
		$odd=$odd?0:1;
		$name=$row->last_name;
		if ($name) $name.=" "; $name.=$row->first_name;
		$sip_uri="sip:".$row->user_id."@".$config->default_domain;
	?>
	<tr valign="top" <?echo $odd?'bgcolor="#FFFFFF"':'bgcolor="#EAF0F4"';?>>
	<td align="left" class="f12" width="160">&nbsp;<?echo $name;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12" width="205">&nbsp;<?echo $sip_uri;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12" width="125">&nbsp;<?echo $row->timezone;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="125"><a href="<?$sess->purl("phonebook.php?kvrk=".uniqID("")."&okey_x=1&fname=".RawURLEncode($row->first_name)."&lname=".RawURLEncode($row->last_name)."&sip_uri=".RawURLEncode($sip_uri));?>">add to phonebook</a></td>
	</tr>
	<?}?>
	</table>
</td></tr>
</table>
	<?if (MySQL_num_rows($find_res)==$config->max_showed_rows){?>
<br><div align="center">The search generated too many matches, please be more specific</div>
<?	}?>
<?}?>

<br>&nbsp;<a href="<?$sess->purl("phonebook.php?kvrk=".uniqid(""));?>">back to phonebook</a><br>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

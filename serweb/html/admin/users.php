<?
/*
 * $Id: users.php,v 1.3 2002/09/10 15:59:35 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

$f = new form;                   // create a form object

if (!$sess->is_registered('sess_usrnm')) $sess->register('sess_usrnm');
if (!$sess->is_registered('sess_fname')) $sess->register('sess_fname');
if (!$sess->is_registered('sess_lname')) $sess->register('sess_lname');
if (!$sess->is_registered('sess_email')) $sess->register('sess_email');

if (!$sess->is_registered('sess_act_row')) $sess->register('sess_act_row');
if (!$sess->is_registered('sess_admin')) {$sess->register('sess_admin'); $sess_admin=1;}

if (isset($usrnm)) $sess_usrnm=$usrnm;
if (isset($fname)) $sess_fname=$fname;
if (isset($lname)) $sess_lname=$lname;
if (isset($email)) $sess_email=$email;

if (isset($act_row)) $sess_act_row=$act_row;
if (!$sess_act_row or isset($okey_x)) $sess_act_row=0;

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="can´t connect to sql server"; break;}

	if ($dele_id){ //delete user
		$q="delete from ".$config->table_aliases." where contact='sip:".$dele_id."@".$config->default_domain."'";
		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
	
		$q="delete from ".$config->table_subscriber." where user_id='$dele_id'";
		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		
        Header("Location: ".$sess->url("users.php?kvrk=".uniqID("")."&message=".RawURLEncode("user seleted succesfully")));
		page_close();
		exit;
	}
}while (false);
	
$f->add_element(array("type"=>"text",
                             "name"=>"usrnm",
							 "size"=>11,
							 "maxlength"=>50,
                             "value"=>$sess_usrnm,
							 "extrahtml"=>"style='width:120px;'"));
$f->add_element(array("type"=>"text",
                             "name"=>"fname",
							 "size"=>11,
							 "maxlength"=>25,
                             "value"=>$sess_fname,
							 "extrahtml"=>"style='width:120px;'"));
$f->add_element(array("type"=>"text",
                             "name"=>"lname",
							 "size"=>11,
							 "maxlength"=>45,
                             "value"=>$sess_lname,
							 "extrahtml"=>"style='width:120px;'"));
$f->add_element(array("type"=>"text",
                             "name"=>"email",
							 "size"=>11,
							 "maxlength"=>50,
                             "value"=>$sess_email,
							 "extrahtml"=>"style='width:120px;'"));
$f->add_element(array("type"=>"submit",
                             "name"=>"okey",
                             "src"=>$config->img_src_path."butons/b_find.gif",
							 "extrahtml"=>"alt='find'"));

do{
	if ($db){

		$query_c="";
		if ($sess_usrnm) $query_c.="user_id like '%$sess_usrnm%' and ";
		if ($sess_fname) $query_c.="first_name like '%$sess_fname%' and ";
		if ($sess_lname) $query_c.="last_name like '%$sess_lname%' and ";
		if ($sess_email) $query_c.="email_address like '%$sess_email%' and ";
		$query_c.="1 ";
	
		// get num of users
		$q="select count(*) from ".$config->table_subscriber." where ".$query_c;
		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		
		$row=MySQL_Fetch_Row($res);
		$num_rows=$row[0];
	
		// get users
		$q="select user_id, first_name, last_name, phone, email_address from ".$config->table_subscriber." where ".$query_c." order by user_id limit $sess_act_row,".$config->num_of_showed_items;
		$users_res=MySQL_Query($q);
		if (!$users_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}
		
	}
						 
}while (false);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>iptel.org, the IP Telephony Site</title>
<?print_html_head();?>
<script language="JavaScript">
<!--
function confirmDelete(theLink){
    var is_confirmed = confirm("Realy you want delete user?");
    if (is_confirmed) {
        theLink.href;
    }
    return is_confirmed;
}
//-->
</script>
</head>
<?
	print_html_body_begin(false, true, true);
	echo "<br>";
	print_errors($errors);                    // Display error
	print_message($message);
?>

	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr><td class="title" width="480">filter:</td></tr>
	</table>

<?$f->start("form");				// Start displaying form?>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td class="f12b">username</td>
	<td class="f12b">first name</td>
	<td class="f12b">last name</td>
	<td class="f12b">email</td>
	</tr>
	<tr>
	<td><?$f->show_element("usrnm");?></td>
	<td><?$f->show_element("fname");?></td>
	<td><?$f->show_element("lname");?></td>
	<td><?$f->show_element("email");?></td>
	</tr>
	<tr><td colspan="4" align="right"><?$f->show_element("okey");?></td></tr>
	</table>
<?$f->finish();					// Finish form?>

<br clear="all"><br>

<?if ($users_res and MySQL_num_rows($users_res)){?>
<table border="0" cellpadding="2" cellspacing="0" bgcolor="#C1D773" align="center">
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
	<tr>
	<td class="titleT" width="85">username</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="100">name</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="70">phone</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="125">email</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="40">&nbsp;</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="63">&nbsp;</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td class="titleT" width="55">&nbsp;</td>
	</tr>
	<tr><td colspan="13" height="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td></tr>
	<?$odd=0;
	while ($row=MySQL_Fetch_Object($users_res)){
		$odd=$odd?0:1;
		$name=$row->last_name;
		if ($name) $name.=" "; $name.=$row->first_name;
	?>
	<tr valign="top" <?echo $odd?'bgcolor="#FFFFFF"':'bgcolor="#EAF0F4"';?>>
	<td align="left" class="f12" width="85">&nbsp;<?echo $row->user_id;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12" width="100">&nbsp;<?echo $name;?></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="right" class="f12" width="70"><?echo $row->phone;?>&nbsp;</td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="left" class="f12" width="125">&nbsp;<a href="mailto:<?echo $row->email_address;?>"><?echo $row->email_address;?></a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="40"><a href="<?$sess->purl("acl.php?kvrk=".uniqid('')."&user_id=".rawURLEncode($row->user_id));?>">ACL</a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="63"><a href="<?$sess->purl("../user_interface/my_account.php?kvrk=".uniqid('')."&uid=".rawURLEncode($row->user_id));?>">account</a></td>
	<td width="2" bgcolor="#C1D773"><img src="<?echo $config->img_src_path;?>title/green_pixel.gif" width="2" height="2"></td>
	<td align="center" class="f12" width="55"><a href="<?$sess->purl("users.php?kvrk=".uniqid('')."&dele_id=".rawURLEncode($row->user_id));?>" onclick="return confirmDelete(this)">delete</a></td>
	</tr>
	<?}?>
	</table>
</td></tr>
</table>
<?}?>

<? if ($num_rows){?>
<p align="center">Showed users <?echo ($sess_act_row+1)." - ".((($sess_act_row+$config->num_of_showed_items)<$num_rows)?($sess_act_row+$config->num_of_showed_items):$num_rows);?> from <?echo $num_rows;?>
<?}else{?>
<p align="center">No users found.
<?}?><br>


<div align="left">
<?
	$url="users.php?kvrk=".uniqid("")."&act_row=";
	print_search_links($sess_act_row, $num_rows, $config->num_of_showed_items, $url);
?>
</div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

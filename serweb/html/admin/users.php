<?
/*
 * $Id: users.php,v 1.12 2004/03/11 22:30:00 kozlik Exp $
 */

require "prepend.php";
require "../../phplib/oohforms.inc";

put_headers();

page_open (array("sess" => "phplib_Session",
				 "auth" => "phplib_Pre_Auth",
				 "perm" => "phplib_Perm"));
$perm->check("admin");

if (!$sess->is_registered('sess_fusers')) $sess->register('sess_fusers');
if (!isset($sess_fusers)) $sess_fusers=new Cfusers();

if (!$sess->is_registered('sess_admin')) {$sess->register('sess_admin'); $sess_admin=1;}

$sess_fusers->init();

do{
	$db = connect_to_db();
	if (!$db){ $errors[]="cannot connect to sql server"; break;}

	if ($dele_id){ //delete user
		$q="delete from ".$config->table_aliases." where contact='sip:".$dele_id."@".$config->default_domain."'";
		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		$q="delete from ".$config->table_subscriber." where username='$dele_id' and domain='$config->default_domain'";
		$res=MySQL_Query($q);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

        Header("Location: ".$sess->url("users.php?kvrk=".uniqID("")."&message=".RawURLEncode("user seleted succesfully")));
		page_close();
		exit;
	}
}while (false);

do{
	if ($db){

		$query_c=$sess_fusers->get_query_where_phrase('s');

		// get num of users
		if ($sess_fusers->onlineonly)
			$q1="select distinct s.username from ".$config->table_subscriber." s, ".$config->table_location." l ".
				" where s.username=l.username and s.domain=l.domain and s.domain='$config->realm' and ".$query_c;
		else
			$q1="select s.username from ".$config->table_subscriber." s ".
				" where s.domain='$config->realm' and ".$query_c;

		$res=MySQL_Query($q1);
		if (!$res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

		$num_rows=MySQL_Num_Rows($res);

		// get users
		if ($sess_fusers->onlineonly)
			$q="select distinct s.username, s.first_name, s.last_name, s.phone, s.email_address from ".
				$config->table_subscriber." s, ".$config->table_location." l ".
				" where s.username=l.username and s.domain=l.domain and ".$query_c.
				" order by s.username limit ".$sess_fusers->act_row.", ".$config->num_of_showed_items;
		else
			$q="select s.username, s.first_name, s.last_name, s.phone, s.email_address from ".$config->table_subscriber." s ".
				" where s.domain='$config->realm' and ".$query_c.
				" order by s.username limit ".$sess_fusers->act_row.", ".$config->num_of_showed_items;
		$users_res=MySQL_Query($q);
		if (!$users_res) {$errors[]="error in SQL query, line: ".__LINE__; break;}

	}

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>
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
<?
print_html_body_begin($page_attributes);
?>

	<h2 class="swTitle">filter:</h2>

<?$sess_fusers->print_form();?>


<?if ($users_res and MySQL_num_rows($users_res)){?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>username</th>
	<th>name</th>
	<th>phone</th>
	<th>email</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	</tr>
	<?$odd=0;
	while ($row=MySQL_Fetch_Object($users_res)){
		$odd=$odd?0:1;
		$name=$row->last_name;
		if ($name) $name.=" "; $name.=$row->first_name;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($row->username);?></td>
	<td align="left"><?echo nbsp_if_empty($name);?></td>
	<td align="right"><?echo nbsp_if_empty($row->phone);?></td>
	<td align="left"><a href="mailto:<?echo $row->email_address;?>"><?echo nbsp_if_empty($row->email_address);?></a></td>
	<td align="center"><a href="<?$sess->purl("acl.php?kvrk=".uniqid('')."&user_id=".rawURLEncode($row->username));?>">ACL</a></td>
	<td align="center"><a href="<?$sess->purl($config->user_pages_path."my_account.php?kvrk=".uniqid('')."&uid=".rawURLEncode($row->username));?>">account</a></td>
	<td align="center"><a href="<?$sess->purl("users.php?kvrk=".uniqid('')."&dele_id=".rawURLEncode($row->username));?>" onclick="return confirmDelete(this)">delete</a></td>
	</tr>
	<?}?>
	</table>
<?}?>

<? if ($num_rows){?>
<div class="swNumOfFoundRecords">Showed users <?echo ($sess_fusers->act_row+1)." - ".((($sess_fusers->act_row+$config->num_of_showed_items)<$num_rows)?($sess_fusers->act_row+$config->num_of_showed_items):$num_rows);?> from <?echo $num_rows;?></div>
<?}else{?>
<div class="swNumOfFoundRecords">No users found</div>
<?}?><br>


<div class="swSearchLinks">
<?
	$url="users.php?kvrk=".uniqid("")."&act_row=";
	print_search_links($sess_fusers->act_row, $num_rows, $config->num_of_showed_items, $url);
?>
</div>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

<?
/*
 * $Id: users.php,v 1.16 2004/04/14 20:51:31 kozlik Exp $
 */

require "prepend.php";

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
	if (!$data = CData_Layer::create($errors)) break;

	if (isset($_GET['dele_id'])){ //delete user

		if (!$data->dele_sip_user($_GET['dele_id'], $config->default_domain, $errors)) break;

        Header("Location: ".$sess->url("users.php?kvrk=".uniqID("")."&message=".RawURLEncode("user deleted succesfully")));
		page_close();
		exit;
	}
}while (false);

do{
	$users=array();

	if ($data){

		$data->set_act_row($sess_fusers->act_row);

		if (false === $users = $data->get_users($sess_fusers, $config->domain, $errors)) break;
	}

}while (false);

/* ----------------------- HTML begin ---------------------- */
print_html_head();?>

<script language="JavaScript" src="<?echo $config->js_src_path;?>functions.js"></script>
<?
print_html_body_begin($page_attributes);
?>

	<h2 class="swTitle">filter:</h2>

<?$sess_fusers->print_form();?>

<?if (is_array($users) and count($users)){?>

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
	foreach($users as $row){
		$odd=$odd?0:1;
	?>
	<tr valign="top" <?echo $odd?'class="swTrOdd"':'class="swTrEven"';?>>
	<td align="left"><?echo nbsp_if_empty($row->username);?></td>
	<td align="left"><?echo nbsp_if_empty($row->name);?></td>
	<td align="right"><?echo nbsp_if_empty($row->phone);?></td>
	<td align="left"><a href="mailto:<?echo $row->email_address;?>"><?echo nbsp_if_empty($row->email_address);?></a></td>
	<td align="center"><a href="<?$sess->purl("acl.php?kvrk=".uniqid('')."&user_id=".rawURLEncode($row->username));?>">ACL</a></td>
	<td align="center"><a href="<?$sess->purl($config->user_pages_path."my_account.php?kvrk=".uniqid('')."&uid=".rawURLEncode($row->username));?>">account</a></td>
	<td align="center"><a href="<?$sess->purl("users.php?kvrk=".uniqid('')."&dele_id=".rawURLEncode($row->username));?>" onclick="return confirmDelete(this, 'Realy you want delete user?')">delete</a></td>
	</tr>
	<?} //while?>
	</table>
	
	<? if ($data->get_num_rows()){?>
	<div class="swNumOfFoundRecords">Showed users <?echo $data->get_res_from()." - ".$data->get_res_to();?> from <?echo $data->get_num_rows();?></div>

	<div class="swSearchLinks">
	<?
		$url="users.php?kvrk=".uniqid("")."&act_row=";
		print_search_links($data->get_act_row(), $data->get_num_rows(), $data->get_showed_rows(), $url);
	?></div>
	<?} // if ($num_rows)?>

	
<?}else{?>
<div class="swNumOfFoundRecords">No users found</div>
<?} // if (is_array($users) and count($users))?>

<br>
<?print_html_body_end();?>
</html>
<?page_close();?>

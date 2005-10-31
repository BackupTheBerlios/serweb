{* Smarty *}
{* $Id: a_new_user.tpl,v 1.1 2005/10/31 16:35:20 kozlik Exp $ *}

{literal}
<style type="text/css">
	#uname, #domain, #fname, #lname, #email, #phone, #timezone {width:250px;}
</style>	
{/literal}


{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.register_new_user}:</h2>

<div class="swForm">
{$form.start}

	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="uname">{$lang_str.ff_username}:</label></td>
	<td>{$form.uname}</td>
	</tr>
	<tr>
	<td><label for="domain">{$lang_str.ff_domain}:</label></td>
	<td>{$form.domain}</td>
	</tr>
	<tr>
	<td><label for="fname">{$lang_str.ff_first_name}:</label></td>
	<td >{$form.fname}</td>
	</tr>
	<tr>
	<td><label for="lname">{$lang_str.ff_last_name}:</label></td>
	<td>{$form.lname}</td>
	</tr>
	<tr>
	<td><label for="email">{$lang_str.ff_email}:</label></td>
	<td>{$form.email}</td>
	</tr>
	<tr>
	<td><label for="phone">{$lang_str.ff_phone}:</label></td>
	<td>{$form.phone}</td>
	</tr>
	<tr>
	<td><label for="timezone">{$lang_str.ff_timezone}:</label></td>
	<td>{$form.timezone}</td>
	</tr>

	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>

{$form.finish}
</div>

<div class="swBackToMainPage"><a href="{url url='users.php' uniq=1}">{$lang_str.l_back_to_main}</a></div>

<br>

{include file='_tail.tpl'}



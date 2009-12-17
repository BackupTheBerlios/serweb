{* Smarty *}
{* $Id: a_new_user.tpl,v 1.4 2009/12/17 17:12:19 kozlik Exp $ *}

{literal}
<style type="text/css">
	#uname, #domain, #sw_fname, #sw_lname, #sw_email, #sw_phone, #sw_timezone {width:250px;}
</style>	
{/literal}


{include file='_head.tpl'}
{include file='_popup_init.tpl'}


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

	{include file="_attr_form.tpl" attributes=$attributes form=$form}

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



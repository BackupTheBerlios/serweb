{* Smarty *}
{* $Id: _form_a_users.tpl,v 1.3 2005/01/31 08:56:44 kozlik Exp $ *}

{literal}
<style type="text/css">
	#usrnm {width:120px;}
	#fname {width:120px;}
	#lname {width:120px;}
	#email {width:120px;}
</style>	
{/literal}

<div class="swForm swHorizontalForm">
{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td><label for="usrnm">{$lang_str.ff_username}</label></td>
<td><label for="fname">{$lang_str.ff_first_name}</label></td>
<td><label for="lname">{$lang_str.ff_last_name}</label></td>
<td><label for="email">{$lang_str.ff_email}</label></td>
</tr>

<tr>
<td>{$form.usrnm}</td>
<td>{$form.fname}</td>
<td>{$form.lname}</td>
<td>{$form.email}</td>
</tr>

<tr><td colspan="4"><label for="onlineonly" style="display: inline;">{$lang_str.ff_show_online_only}:</label>{$form.onlineonly}</td></tr>
				
<tr><td colspan="4" align="right">{$form.okey}</td></tr>
</table>
{$form.finish}
</div>

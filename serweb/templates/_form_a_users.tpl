{* Smarty *}
{* $Id: _form_a_users.tpl,v 1.2 2004/08/10 17:33:50 kozlik Exp $ *}

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

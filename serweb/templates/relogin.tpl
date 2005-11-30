{* Smarty *}
{* $Id: relogin.tpl,v 1.2 2005/11/30 09:58:17 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="frameHeading">{$lang_str.session_expired}</div>

<div class="frameBodyPadding" style="font-weight:bolder; color:red;">
	{$lang_str.session_expired_relogin}
</div>

<br>

<div class="swForm swLoginForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td><label for="username">{$lang_str.ff_username}:</label></td>
		<td>{$form.username}</td>
	</tr>
	<tr>
		<td><label for="passw">{$lang_str.ff_password}:</label></td>
		<td>{$form.password}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align=right>{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

<br>
{include file='_tail.tpl'}

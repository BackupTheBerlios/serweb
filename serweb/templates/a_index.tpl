{* Smarty *}
{* $Id: a_index.tpl,v 1.3 2005/04/21 15:09:46 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="swLPTitle">
<h1>{$domain} {$lang_str.adminlogin}</h1>
{$lang_str.enter_username_and_passw}:
</div>

<div class="swForm swLoginForm">

{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td width="150"><label for="uname">{$lang_str.ff_username}:</label></td>
<td>{$form.uname}</td>
</tr>
<tr>
<td><label for="passw">{$lang_str.ff_password}:</label></td>
<td>{$form.passw}</td>
</tr>
<tr>
<td>&nbsp;</td>
<td align=right>{$form.okey}</td>
</tr>
<tr>
<td colspan="2">{$form.remember_uname}{$lang_str.remember_uname}</td>
</tr>
</table>
{$form.finish}
</div>

<br>
{include file='_tail.tpl'}


{* Smarty *}
{* $Id: a_index.tpl,v 1.2 2004/08/10 17:33:50 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="swLPTitle">
<h1>{$domain} {$lang_str.adminlogin}</h1>
{$lang_str.enter_username_and_passw}:
</div>

<div class="swForm swLoginForm">

{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td><label for="uname">{$lang_str.ff_username}:</label></td>
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
</table>
{$form.finish}
</div>

<br>
{include file='_tail.tpl'}


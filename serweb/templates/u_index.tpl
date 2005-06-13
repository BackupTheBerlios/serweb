{* Smarty *}
{* $Id: u_index.tpl,v 1.5 2005/06/13 13:15:37 kozlik Exp $ *}

{include file='_head.tpl'}

{if ($form_ls)}
	<div align="right">
		{$form_ls.start}
		Language: {$form_ls.ls_language} {$form_ls.okey}
		{$form_ls.finish}
	</div>
{/if}

<div class="swLPTitle">
<h1>{$domain} {$lang_str.userlogin}</h1>
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

<div class="swLPSubscLinks">
	<span class="swLPForgotPass"><a href="{url url='reg/get_pass.php'}">{$lang_str.l_forgot_passw}</a></span>
	<span class="swLPSubscribe"><a href="{url url='reg/index.php'}">{$lang_str.l_register}</a></span>
</div>

<br>
{include file='_tail.tpl'}


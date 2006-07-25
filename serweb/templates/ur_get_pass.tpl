{* Smarty *}
{* $Id: ur_get_pass.tpl,v 1.4 2006/07/25 08:41:12 kozlik Exp $ *}

{include file='_head.tpl'}

{if $action == "pass_was_sended"}
<div class="swForgotPassw">
	<h2><font face="Arial" color="#000000">{$lang_str.forgot_pass_head}</font></h2>
	{$lang_str.forgot_pass_sended}
</div>

{else}

<div class="swForgotPassw">
	<h2>{$lang_str.forgot_pass_head}</h2>
	<p>{$lang_str.forgot_pass_introduction}</p>
</div>

<br>
<div class="swForm swHorizontalForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td><label for="fp_uname">{$lang_str.ff_username}:</label></td>
		<td>{$form.fp_uname}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><br />{$form.okey}</td>
	</table>
{$form.finish}
</div>
{/if}

<br>
<div class="swBackToMainPage"><a href="{url url='../index.php'}">{$lang_str.l_back_to_loginform}</a></div>
{include file='_tail.tpl'}


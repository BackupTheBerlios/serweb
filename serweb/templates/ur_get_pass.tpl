{* Smarty *}
{* $Id: ur_get_pass.tpl,v 1.2 2005/04/26 21:12:16 kozlik Exp $ *}

{include file='_head.tpl'}

{if $action == "pass_was_sended"}
<div class="swForgotPassw">
	<h2><font face="Arial" color="#000000">{$lang_str.forgot_pass_head}</font></h2>
	{$lang_str.forgot_pass_sended}
</div>

{else}

<div class="swForgotPassw">
	<h2><font face="Arial" color="#000000">{$lang_str.forgot_pass_head}</font></h2>
	{$lang_str.forgot_pass_introduction}
</div>

<hr size=1>
<br>
<div class="swForm swHorizontalForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center"><tr>
	<td><label for="fp_uname">{$lang_str.ff_username}:</label></td>
	<td>{$form.fp_uname}</td>
	<td>&nbsp;{$form.okey}</td>
	</tr></table>
{$form.finish}
</div>
{/if}

<br>
<hr size=1>
<div align="center"><a href="{url url='../index.php'}">{$lang_str.l_back_to_loginform}</a>.</div>
<hr size=1>
{include file='_tail.tpl'}


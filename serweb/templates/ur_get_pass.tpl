{* Smarty *}
{* $Id: ur_get_pass.tpl,v 1.1 2004/08/10 17:33:50 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="swForgotPassw">
	<h2><font face="Arial" color="#000000">{$lang_str.forgot_pass_head}</font></h2>
	{$lang_str.forgot_pass_introduction}
</div>

<hr size=1>

<div class="swForm swHorizontalForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center"><tr>
	<td><label for="uname">{$lang_str.ff_username}:</label></td>
	<td>{$form.uname}</td>
	<td>{$form.okey}</td>
	</tr></table>
{$form.finish}
</div>


<br>
<hr size=1>
<div align="center"><a href="{url url='../index.php'}">{$lang_str.l_back_to_loginform}</a>.</div>
<hr size=1>
{include file='_tail.tpl'}


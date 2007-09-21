{* Smarty *}
{* $Id: u_index.tpl,v 1.9 2007/09/21 14:21:21 kozlik Exp $ *}

{include file='_head.tpl'}

{literal}
<style type="text/css">
	#uname {width:97%;}
	#passw {width:97%;}
</style>	
{/literal}

{if ($form_ls)}
	<div class="selectlanguage">
		{$form_ls.start}
		{$lang_str.ff_language}: {$form_ls.ls_language} <br /><br />{$form_ls.okey}
		{$form_ls.finish}
	</div>
{/if}

<div class="swLPTitle">
<h1>{$domain} {$lang_str.userlogin}</h1>
<p>{$lang_str.enter_username_and_passw}:</p>
</div>

<div class="swForm swLoginForm">

{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td width="50%"><label for="uname">{$lang_str.ff_username}:</label></td>
<td>{$form.uname}</td>
</tr>
<tr>
<td width="50%"><label for="passw">{$lang_str.ff_password}:</label></td>
<td>{$form.passw}</td>
</tr>
<tr>
<td colspan="2">{$form.remember_uname}{$lang_str.remember_uname}</td>
</tr>
</table>
<div class="loginButton">{$form.okey}</div>
{$form.finish}
</div>

<div class="swLPSubscLinks">
	<span class="swLPForgotPass"><a href="{url url='reg/get_pass.php'}">{$lang_str.l_forgot_passw}</a></span>
	{if $allow_register}<span class="swLPSubscribe"><a href="{url url='reg/index.php'}">{$lang_str.l_register}</a></span>{/if}
	<span class="swLPSubscribe"><a href="{url url='reg/reg_domain_1.php'}">{$lang_str.l_have_my_domain}</a></span>
</div>
<div style="clear:both;"></div>

<br>
{include file='_tail.tpl'}


{* Smarty *}
{* $Id: a_index.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="swLPTitle">
<h1>{$domain} Adminlogin</h1>
Please enter your username and password:
</div>

<div class="swForm swLoginForm">

{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td><label for="uname">Username:</label></td>
<td>{$form.uname}</td>
</tr>
<tr>
<td><label for="passw">Password:</label></td>
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

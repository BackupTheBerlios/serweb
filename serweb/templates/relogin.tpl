{* Smarty *}
{* $Id: relogin.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="frameHeading">Session expired</div>
<div class="frameBodyPadding" style="font-weight:bolder; color:red;">
Your session expired, please relogin.
</div>
<br>

<div class="swForm swLoginForm">
<form name='login_form' action="{$form_action}" method=post>
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td><label for="username">Username:</label></td>
<td><input type="text" name="username" id="username" value="{$form_username}" size=32 maxlength=32 disabled></td>
</tr>
<tr>
<td><label for="passw">Password:</label></td>
<td><input type="password" name="password" id="password" size=32 maxlength=32></td>
</tr>
<tr>
<td>&nbsp;</td>
<td align=right><input type="Submit" name="relogin" value="Login"></td>
</tr>
</table>
</form>
</div>


<br>
{include file='_tail.tpl'}


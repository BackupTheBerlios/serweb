{* Smarty *}
{* $Id: _form_a_list_of_admins.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

<div class="swForm swHorizontalForm">
{$form.start}
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td><label for="usrnm">username</label></td>
<td><label for="domain">domain</label></td>
<td><label for="fname">first name</label></td>
<td><label for="lname">last name</label></td>
<td><label for="email">email</label></td>
</tr>

<tr>
<td>{$form.usrnm}</td>
<td>{$form.domain}</td>
<td>{$form.fname}</td>
<td>{$form.lname}</td>
<td>{$form.email}</td>
</tr>

<tr><td colspan="5"><label for="adminsonly" style="display: inline;">show admins only:</label>{$form.adminsonly}</td></tr>
				
<tr><td colspan="5" align="right">{$form.okey}</td></tr>
</table>
{$form.finish}
</div>

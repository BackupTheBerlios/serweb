{* Smarty *}
{* $Id: ur_index.tpl,v 1.1 2004/08/10 17:33:50 kozlik Exp $ *}

{include file='_head.tpl'}

<p>{$lang_str.registration_introduction}</p>
<br>

<div class="swForm swRegForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="fname">{$lang_str.ff_first_name}:</label></td>
	<td >{$form.fname}</td>
	</tr>
	<tr>
	<td><label for="lname">{$lang_str.ff_last_name}:</label></td>
	<td>{$form.lname}</td>
	</tr>
	<tr>
	<td><label for="email">{$lang_str.ff_email}:</label></td>
	<td>{$form.email}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td><div class="swRegFormDesc">{$lang_str.reg_email_desc}</div></td>
	</tr>
	<tr>
	<td><label for="phone">{$lang_str.ff_phone}:</label></td>
	<td>{$form.phone}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td><div class="swRegFormDesc">{$lang_str.reg_phone_desc}</div></td>
	</tr>
	<tr>
	<td><label for="timezone">{$lang_str.ff_your_timezone}:</label></td>
	<td>{$form.timezone}</td>
	</tr>
	<tr>
	<td><label for="uname">{$lang_str.ff_pick_username}:</label></td>
	<td>{$form.uname}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td><div class="swRegFormDesc">{$lang_str.reg_username_desc}</div></td>
	</tr>
	<tr>
	<td><label for="passwd">{$lang_str.ff_pick_password}:</label></td>
	<td>{$form.passwd}</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td><div class="swRegFormDesc">{$lang_str.reg_password_desc}</div></td>
	</tr>
	<tr>
	<td><label for="passwd_r">{$lang_str.ff_confirm_password}:</label></td>
	<td>{$form.passwd_r}</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td colspan="2" class="swHorizontalForm"><label for="terms">{$lang_str.ff_terms_and_conditions}:</label></td></tr>
	<tr><td colspan="2">{$form.terms}</td></tr>
	<tr><td colspan="2" class="swHorizontalForm">{$form.accept} <label for="accept" style="display:inline;">{$lang_str.ff_i_accept}</label></td></tr>
	<tr><td colspan="2" align="right">{$form.okey}&nbsp;&nbsp;&nbsp;</td></tr>
	</table>
{$form.finish}
</div>


<br>
<hr>
<div align="center"><a href="{url url='../index.php'}">{$lang_str.l_back_to_loginform}</a>.</div>
<hr>
{include file='_tail.tpl'}


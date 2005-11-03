{* Smarty *}
{* $Id: 2_new_admin.tpl,v 1.1 2005/11/03 11:02:11 kozlik Exp $ *}

{literal}
<style type="text/css">
	#uname, #domain, #fname, #lname, #email, #phone, #timezone {width:250px;}

	#link_close{
		float: left;
		text-align: left;
	}

	#link_skip{
		float: right;
		text-align: right;
	}
</style>	
{/literal}


{include file='_head.tpl'}

<h2 class="swTitle">{$lang_str.assign_admin_to_domain}</h2>

<div class="swLinkSelector"><span class="swLsSelected">{$lang_str.register_new_admin}</span> | <span class="swLsNotSelected"><a href="{url url='2_existing_admin.php' uniq=1}">{$lang_str.assign_existing_admin}</a></span></div>
<br /><br />


<div class="swForm">
{$form.start}

	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="uname">{$lang_str.ff_username}:</label></td>
	<td>{$form.uname}</td>
	</tr>
	<tr>
	<td><label for="domain">{$lang_str.ff_domain}:</label></td>
	<td>{$form.domain}</td>
	</tr>
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
	<td><label for="phone">{$lang_str.ff_phone}:</label></td>
	<td>{$form.phone}</td>
	</tr>
	<tr>
	<td><label for="timezone">{$lang_str.ff_timezone}:</label></td>
	<td>{$form.timezone}</td>
	</tr>

	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>

{$form.finish}
</div>

<div class="swBackToMainPage" id="link_close"><a href="javascript: window.close();">{$lang_str.l_close_window}</a></div>
<div class="swBackToMainPage" id="link_skip" ><a href="3_finish.php">{$lang_str.l_skip_asignment_of_admin}</a></div>

<br>
{include file='_tail.tpl'}



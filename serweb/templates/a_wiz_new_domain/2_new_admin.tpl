{* Smarty *}
{* $Id: 2_new_admin.tpl,v 1.2 2005/12/22 13:39:35 kozlik Exp $ *}

{literal}
<style type="text/css">
	#uname, #domain, #sw_fname, #sw_lname, #sw_email, #sw_phone, #sw_timezone {width:250px;}

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

	{include file="_attr_form.tpl" attributes=$attributes form=$form}

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



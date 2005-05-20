{* Smarty *}
{* $Id: u_my_account.tpl,v 1.7 2005/05/20 17:10:20 kozlik Exp $ *}

{include file='_head.tpl'}

{if $come_from_admin_interface}
<div class="swNameOfUser">{$lang_str.user}: {$user_auth->uname}</div>
{/if}

<div class="swForm">
{$form_pd.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
{if $enabled_fields.email}
	<tr>
	<td><label for="pd_email">{$lang_str.ff_your_email}:</label></td>
	<td>{$form_pd.pd_email}</td>
	</tr>
{/if}
	<tr>
	<td><label for="pd_allow_find">{$lang_str.ff_allow_lookup_for_me}:</label></td>
	<td>{$form_pd.pd_allow_find}</td>
	</tr>

{if $config->enable_forward_to_voicemail}
	{assign var='f_element' value=$config->enable_forward_to_voicemail}
	<tr>
	<td><label for="{$config->enable_forward_to_voicemail}">{$lang_str.ff_fwd_to_voicemail}:</label></td>
	<td>{$form_pd.$f_element}</td>
	</tr>
{/if}

{if $config->enable_status_visibility}
	{assign var='f_element' value=$config->enable_status_visibility}
	<tr>
	<td><label for="{$config->enable_status_visibility}">{$lang_str.ff_status_visibility}:</label></td>
	<td>{$form_pd.$f_element}</td>
	</tr>
{/if}


	<tr>
	<td><label for="pd_timezone">{$lang_str.ff_your_timezone}:</label></td>
	<td>{$form_pd.pd_timezone}</td>
	</tr>
{if $enabled_fields.pass}
	<tr>
	<td><label for="pd_passwd">{$lang_str.ff_your_password}:</label></td>
	<td>{$form_pd.pd_passwd}</td>
	</tr>
	<tr>
	<td><label for="pd_passwd_r">{$lang_str.ff_retype_password}:</label></td>
	<td>{$form_pd.pd_passwd_r}</td>
	</tr>
{/if}
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form_pd.okey}</td>
	</tr>
	</table>
{$form_pd.finish}
</div>



{foreach from=$aliases item='row' name='aliases'}
	{if $smarty.foreach.aliases.first}
	<div id="swMAAliasesTable">
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr><th>{$lang_str.your_aliases}:</th></tr>
	{/if}
	<tr><td align="center">{$row.username}</td></tr>
	{if $smarty.foreach.aliases.last}
	</table>
	</div>
	{/if}
{/foreach}

	
{foreach from=$acl item='row' name='acl'}
	{if $smarty.foreach.acl.first}
	<div id="swMAACLTable">
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr><th>{$lang_str.your_acl}:</td></tr>
	{/if}
	<tr><td align="center">{$row.grp}</td></tr>
	{if $smarty.foreach.acl.last}
	</table>
	</div>
	{/if}
{/foreach}
	
<br class="swCleaner"><br>

{foreach from=$usrloc item='row' name='usrloc'}
	{if $smarty.foreach.usrloc.first}
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr>
	<th>{$lang_str.th_contact}</th>
	<th>{$lang_str.th_expires}</th>
	<th>{$lang_str.th_priority}</th>
	<th>{$lang_str.th_location}</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	<tr valign="top">
	<td align="left">{$row.uri|empty2nbsp}</td>
	<td align="center">{$row.expires|empty2nbsp}</td>
	<td align="center">{$row.q|empty2nbsp}</td>
	<td align="center">{$row.geo_loc|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_dele}">{$lang_str.l_delete}</a></td>
	</tr>
	{if $smarty.foreach.usrloc.last}
	</table>
	{/if}
{/foreach}
	
<h2 class="swTitle">{$lang_str.add_new_contact}:</h2>

<div class="swForm">
{$form_ul.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="sip_address">{$lang_str.ff_sip_address}:</label></td>
	<td>{$form_ul.ul_sip_address}</td>
	<td><label for="expires">{$lang_str.ff_expires}:</label></td>
	<td>{$form_ul.ul_expires}</td>
	<td>{$form_ul.okey}</td></tr>
	</table>
{$form_ul.finish}
</div>

{if $config->enable_dial_voicemail or $config->enable_test_firewall}
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
{if $config->enable_dial_voicemail}<td align="center"><a href="{$url_ctd}"><img src="{$cfg->img_src_path}int/{$lang_set.ldir}/buttons/btn_dial_your_voicemail.gif" width="165" height="16" border="0"></a></td>{/if}
{if $config->enable_test_firewall}<td align="center"><a href="{$url_stun}"><img src="{$cfg->img_src_path}int/{$lang_set.ldir}/buttons/btn_test_firewall_NAT.gif" width="165" height="16" border="0"></a></td>{/if}
</tr>
</table>
{/if}


{if $come_from_admin_interface}
	<div class="swBackToMainPage"><a href="{$url_admin}">{$lang_str.l_back_to_main}</a></div>
{/if}
<br>
{include file='_tail.tpl'}


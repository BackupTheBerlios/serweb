{* Smarty *}
{* $Id: u_my_account.tpl,v 1.12 2005/12/14 16:27:11 kozlik Exp $ *}

{include file='_head.tpl'}

{if $come_from_admin_interface}
<div class="swNameOfUser">{$lang_str.user}: {$user_auth->uname}</div>
{/if}

<div class="swForm">
{$form_pd.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="pu_passwd">{$lang_str.ff_your_password}:</label></td>
	<td>{$form_pd.pu_passwd}</td>
	</tr>
	<tr>
	<td><label for="pu_passwd_r">{$lang_str.ff_retype_password}:</label></td>
	<td>{$form_pd.pu_passwd_r}</td>
	</tr>

{foreach from=$attributes item='row' name='attributes'}
 	{if $row.att_type == 'radio'}
 		
 		<tr><td colspan=2>&nbsp;</td></tr>
 		{foreach from=$row.att_spec item='r'}
 			{assign var='f_element' value="`$row.att_name`_`$r.value`"}
 			<tr>
 			<td><label for="{$row.att_name}_{$r.value}">{$r.label}:</label></td>
 			<td>{$form_pd.$f_element}</td>
 			</tr>
 		{/foreach}
 		<tr><td colspan=2>&nbsp;</td></tr>
 		
 	{else}
 		{assign var='f_element' value=$row.att_name}
 		<tr>
 		<td><label for="{$row.att_name}">{$row.att_desc}:</label></td>
 		<td>{$form_pd.$f_element}</td>
 		</tr>
 	{/if}
{/foreach}

	</tr>
	<tr>
	<td><label for="ls_language">{$lang_str.ff_language}:</label></td>
	<td>{$form_pd.ls_language}</td>
	</tr>

{* If you need display only one specific user preference, see the examples 
 * below. In first case is displayed user preference of name NAME_OF_UP.
 * In second case is displayed user preference which name is stored in
 * variable $name_of_user_pref_1
 *}
 
{*
	<tr>
	<td><label for="NAME_OF_UP">{$attributes.NAME_OF_UP.att_desc}:</label></td>
	<td>{$form_pd.NAME_OF_UP}</td>
	</tr>

	{assign var='f_element' value=$name_of_user_pref_1}
	<tr>
	<td><label for="{$name_of_user_pref_1}">{$attributes.$name_of_user_pref_1.att_desc}:</label></td>
	<td>{$form_pd.$f_element}</td>
	</tr>
*}

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

{if $config->allow_change_usrloc}	

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
{/if}

{if $config->enable_dial_voicemail or $config->enable_test_firewall}
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
{if $config->enable_dial_voicemail}<td align="center"><a href="{$url_ctd}"><img src="{$cfg->img_src_path}int/{$lang_set.ldir}/buttons/btn_dial_your_voicemail.gif" width="165" height="16" border="0" alt="{$lang_str.b_dial_your_voicemail}"></a></td>{/if}
{if $config->enable_test_firewall}<td align="center"><a href="{$url_stun}"><img src="{$cfg->img_src_path}int/{$lang_set.ldir}/buttons/btn_test_firewall_NAT.gif" width="165" height="16" border="0" alt="{$lang_str.b_test_firewall_NAT}"></a></td>{/if}
</tr>
</table>
{/if}


{if $come_from_admin_interface}
	<div class="swBackToMainPage"><a href="{$url_admin}">{$lang_str.l_back_to_main}</a></div>
{/if}
<br>
{include file='_tail.tpl'}


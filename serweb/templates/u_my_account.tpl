{* Smarty *}
{* $Id: u_my_account.tpl,v 1.1 2004/08/09 12:33:56 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="swForm">
{$form.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
{if $config->allow_change_email}
	<tr>
	<td><label for="email">your email:</label></td>
	<td>{$form.email}</td>
	</tr>
{/if}
{if $config->forwarding_to_voicemail_by_group}
	<tr>
	<td><label for="f2voicemail">forwarding to voicemail:</label></td>
	<td>{$form.f2voicemail}</td>
	</tr>
{/if}
	<tr>
	<td><label for="allow_find">allow find me by other users:</label></td>
	<td>{$form.allow_find}</td>
	</tr>
	<tr>
	<td><label for="timezone">your timezone:</label></td>
	<td>{$form.timezone}</td>
	</tr>
{if $config->allow_change_password}
	<tr>
	<td><label for="passwd">your password:</label></td>
	<td>{$form.passwd}</td>
	</tr>
	<tr>
	<td><label for="passwd_r">retype password:</label></td>
	<td>{$form.passwd_r}</td>
	</tr>
{/if}
	<tr>
	<td>&nbsp;</td>
	<td align="right">{$form.okey}</td>
	</tr>
	</table>
{$form.finish}
</div>

{foreach from=$aliases_res item='row' name='aliases'}
	{if $smarty.foreach.aliases.first}
	<div id="swMAAliasesTable">
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr><th>your aliases:</th></tr>
	{/if}
	<tr><td align="center">{$row->username}</td></tr>
	{if $smarty.foreach.aliases.last}
	</table>
	</div>
	{/if}
{/foreach}

	
{foreach from=$acl_res item='row' name='acl'}
	{if $smarty.foreach.acl.first}
	<div id="swMAACLTable">
	<table border="1" cellpadding="1" cellspacing="0" align="center" class="swTable">
	<tr><th>Access-Control-list:</td></tr>
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
	<th>contact</th>
	<th>expires</th>
	<th>priority</th>
	<th>location</th>
	<th>&nbsp;</th>
	</tr>
	{/if}
	<tr valign="top">
	<td align="left">{$row.uri|empty2nbsp}</td>
	<td align="center">{$row.expires|empty2nbsp}</td>
	<td align="center">{$row.q|empty2nbsp}</td>
	<td align="center">{$row.geo_loc|empty2nbsp}</td>
	<td align="center"><a href="{$row.url_dele}">delete</a></td>
	</tr>
	{if $smarty.foreach.usrloc.last}
	</table>
	{/if}
{/foreach}
	

<h2 class="swTitle">add new contact:</h2>

<div class="swForm">
{$form2.start}
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td><label for="sip_address">sip address:</label></td>
	<td>{$form2.sip_address}</td>
	<td><label for="expires">expires:</label></td>
	<td>{$form2.expires}</td>
	<td>{$form2.okey2}</td></tr>
	</table>
{$form2.finish}
</div>



{if $config->enable_dial_voicemail or $config->enable_test_firewall}
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
{if $config->enable_dial_voicemail}<td align="center"><a href="{$url_ctd}"><img src="{$config->img_src_path}butons/b_dial_your_voicemail.gif" width="165" height="16" border="0"></a></td>{/if}
{if $config->enable_test_firewall}<td align="center"><a href="{$url_stun}"><img src="{$config->img_src_path}butons/b_test_firewall_NAT.gif" width="165" height="16" border="0"></a></td>{/if}
</tr>
</table>
{/if}

{if $come_from_admin_interface}
	<div class="swBackToMainPage"><a href="{$url_admin}">back to main page</a></div>
{/if}
<br>
{include file='_tail.tpl'}


{* Smarty *}
{* $Id: reg_confirmation.tpl,v 1.1 2005/04/21 15:09:46 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="rbroundboxB" style="width: 50%; margin: 1em auto;">
<div class="rbtopB"><div><div><span>VoIP SerWeb</span></div></div></div>
<div class="rbcontentwrapB"><div class="rbcontentB">

	<br>
	{if ($action=="successfull")}
		<p>{$lang_str.reg_conf_congratulations}</p>
	{else}
		<p>{$lang_str.reg_conf_failed}<br>
		{$lang_str.reg_conf_contact_infomail}</p>
	{/if}

	{if ($status=="jabber_failed")}
		<p>{$lang_str.reg_conf_set_up}<br>
		<b>{$lang_str.reg_conf_jabber_failed}</b><br>
		{$lang_str.reg_conf_contact_infomail}</p><br>
	{elseif ($status=="already_done")}
		<b>{$lang_str.err_reg_conf_already_created}</b><br>
	{/if}

	<br>
	<div><a href="{url url='../index.php'}"><img src="{$cfg->img_src_path}int/{$lang_set.ldir}/buttons/btn_next.gif" border="0"></a></div>

</div><!-- /rbcontentB -->
</div><!-- /rbcontentwrapB -->
<div class="rbbotB"><div><div></div></div></div>
</div><!-- /rbroundboxB -->

{include file='_tail.tpl'}


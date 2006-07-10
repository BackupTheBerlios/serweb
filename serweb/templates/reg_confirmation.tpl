{* Smarty *}
{* $Id: reg_confirmation.tpl,v 1.5 2006/07/10 13:45:05 kozlik Exp $ *}

{include file='_head.tpl'}

<div class="rbroundboxB" style="width: 50%; margin: 1em auto;">
<div class="rbtopB"><div><div><span>VoIP SerWeb</span></div></div></div>
<div class="rbcontentwrapB"><div class="rbcontentB">

	<br>
	{if ($action=="successfull")}
		<p>{$lang_str.reg_conf_congratulations}</p>
	{else}
		<p>{$lang_str.reg_conf_failed}<br>
		{$lang_str.reg_conf_contact_infomail_1} <a href="mailto:{$infomail}">{$infomail}</a> {$lang_str.reg_conf_contact_infomail_2}</p>
	{/if}

	{if ($status=="jabber_failed")}
		<p>{$lang_str.reg_conf_set_up}<br>
		<b>{$lang_str.reg_conf_jabber_failed}</b><br>
		{$lang_str.reg_conf_contact_infomail_1} <a href="mailto:{$infomail}">{$infomail}</a> {$lang_str.reg_conf_contact_infomail_2}</p><br>
	{elseif ($status=="nr_not_exists")}
		<b>{$lang_str.reg_conf_nr_not_exists}</b><br>
	{/if}

	<br>
	<div><a href="{url url='../index.php'}"><img src="{$cfg->img_src_path}int/{$lang_set.ldir}/buttons/btn_next.gif" border="0" alt="{$lang_str.b_next}"></a></div>

</div><!-- /rbcontentB -->
</div><!-- /rbcontentwrapB -->
<div class="rbbotB"><div><div></div></div></div>
</div><!-- /rbroundboxB -->

<br />

{include file='_tail.tpl'}


{* Smarty *}
{* $Id: ur_confirmation.tpl,v 1.1 2004/08/10 17:33:50 kozlik Exp $ *}

{include file='_head.tpl'}

{if ($result==1)}
	<p>{$lang_str.reg_conf_congratulations}</p>
{elseif ($result>=2 && $result<10)}
	<p>{$lang_str.reg_conf_set_up}<br>
	<b>{$lang_str.reg_conf_jabber_failed}</b><br>
	{$lang_str.reg_conf_contact_infomail}</p>
{elseif ($parameters.errors)}
	<p>{$lang_str.reg_conf_failed}<br>
	{$lang_str.reg_conf_contact_infomail}</p>
{/if}

<br>
<hr>
<div align="center"><a href="{url url='../index.php'}">{$lang_str.l_back_to_loginform}</a>.</div>
<hr>

{include file='_tail.tpl'}


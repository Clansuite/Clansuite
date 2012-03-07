{* {$mibbit_options|var_dump} *}

{include file='mibbit_irc_welcome_message.tpl'}

<p>
    <iframe width={$mibbit_options.width}
            height={$mibbit_options.height}
            scrolling=no
            border=0
            src="http://embed.mibbit.com/e/index.html?nick={$mibbit_options.nick_prefix}{$mibbit_options.nick}&amp;server={$mibbit_options.server}&amp;channel=%23{$mibbit_options.channel}">
     </iframe>
</p>

{include file='mibbit_irc_help_text.tpl'}
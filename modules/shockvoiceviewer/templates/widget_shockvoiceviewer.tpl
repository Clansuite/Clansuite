{* {$serverinfos|@var_dump} *}

<!-- [Start] Widget: Shockvoice Viewer //-->
<div class="td_header">Shockvoice Viewer</div>

<div class="cell1">

{if isset($serverinfos) and $serverinfos.request_ok == true}

    <div style="float:right; clear:both;"><a href="svlink::{$serverinfos.servername}:{$serverinfos.port}">Connect</a></div>

    {$serverinfos.servername} - Shockvoice
    {*
    {if isset($serverinfos.num_clients)}  <br/> {t}Users :{/t}    {$serverinfos.num_clients}  {/if}
    {if isset($serverinfos.num_channels)} {t}Channels :{/t} {$serverinfos.num_channels} {/if}
    *}
    <br />

    {foreach name=channel item=channel from=$serverinfos.channels}

        {if not empty($channel.name)}
            {$channel.image} {$channel.name} <br />
        {/if}


        {if not empty($serverinfos.users)}
            {foreach name=user item=user from=$serverinfos.users}

            {if isset($user.channelid) and isset($channel.id) and ($user.channelid == $channel.id)}
                {$user.name}
            {/if}

            {/foreach}
        {/if}

        {if not empty($channel.subchannels)}
            {foreach item=subchan from=$channel.subchannels}

                &nbsp;
                {if not empty($subchan.name)}
                    {$subchan.image} {$subchan.name}
                    <br/>

                    {if not empty($serverinfos.users)}
                        {foreach name=user item=user from=$serverinfos.users}

                        {if isset($user.channelid) and isset($channel.id) and ($user.channelid == $subchan.id)}
                            &nbsp; &nbsp; {$user.image} {$user.name}
                            <br/>
                        {/if}

                        {/foreach}

                    {/if}

                {/if}

            {/foreach}
        {/if}

    {/foreach}

{else}

    {* ({$serverinfo.servername}:{$serverinfo.server_port}) *}
    <br />
    <span style="color: red; font-weight: bold;">Server offline</span>

{/if}

</div>
<!-- [-End-] Widget: Shockvoice Viewer //-->
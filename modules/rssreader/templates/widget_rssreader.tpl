{* {$feed|@var_dump} *}

<!-- Start: Rssreader Widget from Module Rssreader  //-->

<div class="rss-inside">

    <table class="records" style="width: 100%;">
    <tbody>

        {* Attention: $feed is an object *}
        {foreach from=$feed->get_items() item=i}

            {*
               Use the SimplePie API Methods to access the data of the feeditems.
               You'll find an comprehensive overview (scroll-down) on their website:

               http://simplepie.org/wiki/reference/start

            *}

            <tr>
                <td>
                    <div style="border-bottom: 1px solid #ccccff;">
                        <a href="{$i->get_link()}" target="_blank" style="font-size: 1.3em; font-weight: bold;">{$i->get_title()}</a>
                    </div>
                    <br />{$i->get_description()}
                    <span style="float: right; font-style: italic;">{$i->get_date()}</span>
                </td>
            </tr>

        {/foreach}

    </tbody>
    </table>

</div>

<!-- End: Rssreader Widget Module Template //-->
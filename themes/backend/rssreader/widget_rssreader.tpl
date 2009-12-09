{* {$feed|@var_dump} *}

<!-- ## Start: Rssreader Widget from Module Rssreader ## -->

<div class="rss-inside">

    {* Initialize Accordion with jQuery *}
    {literal}
    <script type="text/javascript">
        jQuery().ready(function(){
            jQuery('#accordion').accordion({
                    autoHeight: false,
                    header: 'h3',
                    collapsible: true
                });
            });
    </script>
    {/literal}

    <!-- ## Start: RssReader Accordion ## /-->
    <div id="accordion">
        {foreach from=$feed->get_items() item=i name=csRSSforeach}

            <h3><a href="#">{$i->get_title()}</a></h3>
            <div>
                <p>
                <span style="float: right; font-size:10px;">{$i->get_date()}</span>
                {$i->get_description()}
                <span style="float:right; font-size:10px;"><a href="{$i->get_link()}" class="more" target="_blank" >[...mehr]</a></span>
                </p>
            </div>

            {* Limit to 5 Entries *}
            {if $smarty.foreach.csRSSforeach.iteration == $items_newswidget} {break} {/if}

        {/foreach}
    </div>
    <!-- ## End: RssReader Accordion ## /-->

</div>

<!-- ## End: Rssreader Widget Module Template ## -->
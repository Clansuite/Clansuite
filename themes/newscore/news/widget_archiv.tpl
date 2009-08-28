{* {$widget_archiv|@var_dump} *}

{* Years *}
{foreach key=year item=year_archiv from=$widget_archiv}

Year(s): {$year} <br />

    {* Months *}
    {foreach key=month item=month_archiv from=$year_archiv}
        
      
        Month(s) : {$month} <br />

        {* Entries *}
        

    {/foreach}

{/foreach}

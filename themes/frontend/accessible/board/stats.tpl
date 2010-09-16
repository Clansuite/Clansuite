Board Statistics (stats.tpl)

<table border="0" cellspacing="0" cellpadding="0" align="center">

    <tr>
        <th>-</th>
        <th>stats</th>
    </tr>

 {*   {foreach item=stats from=$stats} *}
         <tr>
            <td>statsicon</td>
            <td><br />
                Total Topics: x
                <br />
                Total Posts: x
                <br />
                Total Members: x
                <br />
                Newest Member: x
                <br />
                Forum Page Views: x
            </td>
         </tr>
 {*   {/foreach} *}
</table>

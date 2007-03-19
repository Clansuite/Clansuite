{* Debugausgabe des Arrays: *} {$guestbook|@var_dump}
{html_alt_table loop=$guestbook}
<table>
    <tr>
        <th>#</th>
        <th>Author/Nick</th>
        <th>Added</th>
        <th>Email</th>
        <th>ICQ</th>
        <th>Website</th>
        <th>Town</th>
        <th>Text</th>
        <th>IP</th>
        <th>Action</th>
    </tr>
{foreach item=entry from=$guestbook}
    <tr>
        <td>{$entry.gb_id}</td>
        <td>{$entry.gb_added}</td>
        <td>{$entry.gb_nick} <a href='index.php?mod=users&amp;show'>{$entry.gb_nick}</a></td>
        <td>{$entry.gb_email}</td>
        <td>{$entry.gb_icq}</td>
        <td>{$entry.gb_website}</td>
        <td>{$entry.gb_town}</td>
        <td>{$entry.gb_text}</td>
        <td>{$entry.gb_ip}</td>
    </tr>
{/foreach}
</table>
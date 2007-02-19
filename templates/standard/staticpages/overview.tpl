{* Debugausgabe des Arrays:  {$overview|@var_dump} *}



<dl>
{foreach item=staticpage from=$overview}
  <dt>({$staticpage.id}) <a href='index.php?mod=staticpages&amp;page={$staticpage.title}'>{$staticpage.title}</a> {$staticpage.description}</dt>
{/foreach}
</dl>
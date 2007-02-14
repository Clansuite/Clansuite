{* Debugausgabe des Arrays: {$overview|@var_dump} *}

<dl>
{foreach from=$overview item=staticpage}
  <dt>({$staticpage.id}) <a href='index.php?mod=static&page={$staticpage.title}'>{$staticpage.title}</a> {$staticpage.description}</dt>
{/foreach}
</dl>
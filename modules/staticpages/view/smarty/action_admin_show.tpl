{* Debugausgabe des Arrays:  {$overview|var_dump} *}   

<!-- Module Heading -->
<div class="ModuleHeading">{t}Static Pages Overview[/t}</div>
<div class="ModuleHeadingSmall">{t}This page shows all your static pages. You can create, modify and delete them.</div>

<!-- Content -->
<dl>
{foreach item=staticpage from=$overview}
  <dt>({$staticpage.id}) <a href='index.php?mod=staticpages&amp;page={$staticpage.title}'>{$staticpage.title}</a> {$staticpage.description}</dt>
{/foreach}
</dl>
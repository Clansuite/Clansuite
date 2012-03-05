{* Debugausgabe des Arrays:  {$staticpages|var_dump} *}

<!-- Module Heading -->
<div class="ModuleHeading">{t}Static Pages Overview{/t}</div>
<div class="ModuleHeadingSmall">{t}This page shows all your static pages. You can create, modify and delete them.{/t}</div>

<!-- Content -->
<dl>
{foreach item=staticpage from=$staticpages}
    {* Example for displaying template variables, when they are properties of an object *}
    {$staticpage|firebug}
    <dt>
        ({$staticpage->getId()})
        <a href='index.php?mod=staticpages&amp;page={$staticpage->getTitle()}'>{$staticpage->getTitle()}</a>
        {$staticpage->getDescription()}
    </dt>
{/foreach}
</dl>
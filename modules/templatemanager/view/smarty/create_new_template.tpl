<!-- Start of Template - {$templateeditor_relative_filename} -->

>>> PLEASE INSERT YOUR TEMPLATE CONTENT HERE

A good starting point is to modify your action to assign $variablename to the view.
For Smarty the command is $smarty->assign('variablename', $variablecontent);

To debug-display the content of assigned variables or arrays on templateside use
{literal}
{*
    {$variablename|var_dump}
*}
{/literal}

<!-- End of Template - {$templateeditor_relative_filename} -->
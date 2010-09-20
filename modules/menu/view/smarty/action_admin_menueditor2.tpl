{move_to target="pre_head_close"}
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.tree.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.cookie.js"></script>
	
<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}css/jquery-jstree-themes/default/style.css" />
{/move_to}

{modulenavigation}

<div class="ModuleHeading">{t}Adminmenü - Verwaltung{/t}</div>
<div class="ModuleHeadingSmall">{t}With the Menueditor you may modify the menu entries. You can also add new ones or modify or delete existing ones.{/t}</div>

	<script type="text/javascript">
	$(function () {
                    $("#basic_html").tree({ rules : { multitree : true } });
					$("#basic_html2").tree({ rules : { multitree : true	} })
					$("#tree").tree({ rules : { multitree : true } })
				  });
	</script>

<div id="tree">

    {$tree}
    
</div>

<br /><br />

@todo: jquery.tree.js den themepath zur style.css übergeben default ui themepath

<div id="basic_html">
	<ul>
		<li><a href="#"><ins>&nbsp;</ins>Root node 1</a>
			<ul>
				<li id="phtml_2"><a href="#"><ins>&nbsp;</ins>Child node 1</a></li>
				<li id="phtml_3"><a href="#"><ins>&nbsp;</ins>Child node 2</a></li>
				<li id="phtml_4"><a href="#"><ins>&nbsp;</ins>Some other child node with longer text</a></li>
			</ul>
		</li>
		<li id="phtml_5"><a href="#"><ins>&nbsp;</ins>Root node 2</a></li>
	</ul>
</div>

<br>
</br>
<br>
</br>

<div id="basic_html2">
    <ul>
    	<li class="open"><a href="#"><ins>&nbsp;</ins>Root node 1</a>
    		<ul>
    			<li ><a href="#"><ins>&nbsp;</ins>Child node 1</a></li>
    			<li ><a href="#"><ins>&nbsp;</ins>Child node 2</a></li>
    			<li ><a href="#"><ins>&nbsp;</ins>Some other child node with longer text</a></li>
    		</ul>
    	</li>
    	<li ><a href="#"><ins>&nbsp;</ins>Root node 2</a></li>
    </ul>
</div>
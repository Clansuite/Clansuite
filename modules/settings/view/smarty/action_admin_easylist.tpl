{modulenavigation}
<div class="ModuleHeading">{t}Clansuite Settings{/t}</div>
<div class="ModuleHeadingSmall">{t}Konfiguration des Systems.{/t}</div>

<script type="text/javascript">
    /*
     * jQuery toggleControl 1.0
     *
     * Copyright (c) 2008 Darren Oakley
     *
     * http://hocuspokus.net/
     *
     * Dual licensed under the MIT and GPL licenses:
     *   http://www.opensource.org/licenses/mit-license.php
     *   http://www.gnu.org/licenses/gpl.html
     *
     */

    ;(function($) {
        $.fn.extend({
            toggleControl: function( element, options ) {

                var defaults = {
                    hide: true,
                    speed: "normal",
                    event: "click",
                    openClass: "toggle-open",
                    closeClass: "toggle-close"
                };

                var options = $.extend(defaults, options);

                return this.each( function( index ) {
                    var obj = $(this);

                    $(this).each( function ( i, toggle ) {

                        if ( options.hide ) {
                            $(toggle).addClass( options.openClass );
                            $(element).slideUp( options.speed );
                        } else {
                            $(toggle).addClass( options.closeClass );
                        }

                        $(toggle).bind( options.event, function(event) {
                            $(toggle).toggleClass( options.openClass );
                            $(toggle).toggleClass( options.closeClass );
                            $(element).eq(index).slideToggle( options.speed );
                        });
                    });

                });

            }
        });
    })(jQuery);
</script>
<script type="text/javascript" charset="utf-8">
    //<![CDATA[
        $(document).ready( function() {
            $("fieldset.toggleable legend").toggleControl("fieldset.toggleable div", { speed:"slow" });
        });
    //]]>
</script>
<style type="text/css">
fieldset {
	border: 1px solid #ccc;
	margin-bottom: 10px;
    width: 280px;
}

fieldset legend {
	background-color: #adc96d;
	color: #fff;
	border: 1px solid #ccc;
	padding: 4px 6px 4px 6px;
	letter-spacing: 1px;
	text-transform: uppercase;
	font-weight: bold;
}

/* -- Toggle Code -- */

.toggle-open, .toggle-close {
	background-repeat: no-repeat;
	background-position: 0 center;
	cursor: pointer;
	padding: 4px 6px 4px 16px;
}

.toggle-open {
	background-image: url({$www_root_themes_core}/images/bullet_arrow_down.png);
}

.toggle-close {
	background-image: url({$www_root_themes_core}/images/bullet_arrow_up.png);
	background-color: #e0542f;
}
</style>

<form action="index.php?mod=settings&amp;sub=admin&amp;action=update" method="post" accept-charset="UTF-8">

{foreach $config as $section => $array}
    <fieldset class="toggleable">
    <legend><b>{$section|ucfirst}</b></legend>
        <div id="div-{$section}">
        {foreach $array as $key => $value}
        <dl>
            <dt><label for="{$section}-{$key}">{$section}[{$key}]</label></dt>
            <dd><input type="text" 
                 id="{$section}-{$key}" name="config[{$section}][{$key}]" value="{$value}" /></dd>
        </dl>
        {/foreach}
        </div>
    </fieldset>
{/foreach}

<div style="text-align:center">
    <input type="submit" class="ButtonGreen" value="{t}Save Settings{/t}" name="submit" />
</div>

</form>
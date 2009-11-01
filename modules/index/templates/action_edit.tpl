{literal}
<script type="text/javascript">
    window.addEvent('domready', function() {
        // All widgets Container
        var bCont = new Element('div');
        bCont.setProperties( {
            id: 'all_widgets'
        });
        bCont.setStyles( {
            position: 'fixed'
        } );
        bCont.inject($(document.body));
        bCont.out = false;
        var bCont2 = new Element('div');
        
        // A handler to store deleted widgets
        bCont2.setProperties( {
            id: 'all_widgets_cont'

        });
        bCont2.setStyles( {
            width: '2000px',
            height: '100px',
            position: 'absolute',
            padding: '10px'
        });
        bCont2.innerHTML = '&nbsp;';
        
                
        // Click text for all widgets handler
        var bContClick = new Element('span');
        bContClick.setProperties( {
            id: 'all_widgets_handle'
        });
        bContClick.appendText('Show all widgets');
        bContClick.addEvent('click', function() {
            if( bCont.out == false )
            {
                bCont.morph( { height: 250, width: '900' } );
                bCont.setStyles( {
                    overflow: 'auto'
                });
                bCont.out = true;
            }
            else
            {
                bCont.morph( { height: 16, width: 80 } );
                bCont.setStyles( {
                    overflow: 'hidden'
                });
                bCont.out = false;            
            }
                
        });
        
        bContClick.inject(bCont);
        bCont2.inject(bCont);
        
        // Save button
        var saveBar = new Element('div', {
            id: 'widget_save_bar'
        });
        saveBar.inject($(document.body));
        
        var saveButton = new Element('input', {
            id: 'wdget_save_button',
            value: 'Save the widgets...',
            type: 'submit'
        });
        
        saveButton.inject(saveBar);
        
        saveButton.addEvent('click', function(event) {
            event.stop();
            ClanSuiteSort.serialize().each( function(list, i) {
                list.each( function(widget) {
                    alert( i + ": " + widget );
                });
            });
        });
        
        
        
        
        
        $$('.widget').each(function(item)
        {
            item.setStyle('display', 'inline-widget');            
            item.setStyle('position', 'relative');
        });

        var ClanSuiteSort = new Sortables( '#left_widget_bar, #right_widget_bar, #bottom_widget_bar, #all_widgets_cont', {
            revert: { duration: 500, transition: 'elastic:out' },
            opacity: '0.5',
            handle: '.td_header',
            clone: function(a,element,c) {
            return new Element('div').setStyles({
              'margin': '0px',
              'position': 'absolute',
              'visibility': 'hidden',
              'border': '1px solid #333',
              'height': '50px',
              'z-index': '15000',
              'width': element.getStyle('width')
            }).inject(this.list).position(element.getPosition(element.getOffsetParent()));
            
            }
        });
        
        $$('.widget .td_header').each( function(el) {
            el.setStyles( { cursor: 'w-resize' } );
            
            // DELETE widget
            var del = new Element('img').setProperties( { 
                src: '/themes/core/images/crystal_clear/16/editdelete.png'
            } ).setStyles( { 
                right: '5px',
                position: 'absolute',
                cursor: 'pointer'
            } );
            del.inject(el);
            del.addEvent('click', function() {
                el.getParent('span').inject($('all_widgets_cont'));
            });
            
            // WIDTH INCREASE
            el.addEvent('mousewheel', function(event) {
                event = new Event(event).stop();
                if (event.wheel > 0) {
                    el.getParent('span').setStyle('width', el.getParent('span').getSize().x+5);
                }
                
                if (event.wheel < 0) {
                    el.getParent('span').setStyle('width', el.getParent('span').getSize().x-5);
                }                
            });
                        
        });
        

        
        
        /*

        */
        


    });


</script>{/literal}
{literal}
<script language="JavaScript" type="text/javascript">
    window.addEvent('domready', function() {
        // All Blocks Container
        var bCont = new Element('div');
        bCont.setProperties( {
            id: 'all_blocks'
        });
        bCont.inject($(document.body));
        bCont.out = false;
        var bCont2 = new Element('div');
        bCont2.setProperties( {
            id: 'all_blocks_cont'

        });
        bCont2.setStyles( {
            width: '2000px',
            height: '100px',
            position: 'absolute',
            padding: '10px'
        });
        bCont2.innerHTML = '&nbsp;';
        
                
        // Click Text
        var bContClick = new Element('span');
        bContClick.setProperties( {
            id: 'all_blocks_handle'
        });
        bContClick.appendText('Show all blocks');
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
        
        
        $$('.block').each(function(item)
        {
            item.setStyle('display', 'inline-block');            
            item.setStyle('position', 'relative');
        });

        var ClanSuiteSort = new Sortables( '#left_block, #right_block, #bottom_block, #all_blocks_cont', {
            revert: { duration: 500, transition: 'elastic:out' },
            opacity: '0.5',
            handle: '.td_header',
            clone: function(a,element,c) {
            return new Element('div').setStyles({
              'margin': '0px',
              'position': 'absolute',
              'visibility': 'hidden',
              'border': '1px solid #000000',
              'height': '50px',
              'width': element.getStyle('width')
            }).inject(this.list).position(element.getPosition(element.getOffsetParent()));
            
            }
        });
        
        $$('.block .td_header').each( function(el) {
            el.setStyles( { cursor: 'w-resize' } );
            
            // DELETE BLOCK
            var del = new Element('img').setProperties( { 
                src: '/themes/core/images/crystal_clear/16/editdelete.png'
            } ).setStyles( { 
                right: '5px',
                position: 'absolute',
                cursor: 'pointer'
            } );
            del.inject(el);
            del.addEvent('click', function() {
                el.getParent('span').inject($('all_blocks_cont'));
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
        $('test').addEvent('click', function() {
            ClanSuiteSort.serialize().each( function(list, i) {
                list.each( function(block) {
                    alert( i + ": " + block );
                });
            });
        });
        */
        


    });


</script>{/literal}
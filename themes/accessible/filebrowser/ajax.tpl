{move_to target="pre_head_close"}
    
        <script type="text/javascript" src="{$www_root_themes_core}/javascript/ajax.js"></script>
        <script type="text/javascript">
            var global_path="a";
            var global_name="a";
	        /**
            * @desc Send a POST request
            **/
            function sendFilebrowserAjaxRequest(path, name)
	        {

                if( document.getElementById('section-' + name + '-' + path).style.display == 'block' )
                {
                    document.getElementById('node-' + name + '-' + path).src = '{$www_root_themes_core}/admin/adminmenu/images/tree-node.gif';
                    document.getElementById('section-' + name + '-' + path).style.display = 'none';
                    return true;
                }
                else
                {
		            if( document.getElementById('section-' + name + '-' + path).innerHTML != '' )
                    {
                        document.getElementById('section-' + name + '-' + path).style.display = 'block';
                        document.getElementById('node-' + name + '-' + path).src = '{$www_root_themes_core}/admin/adminmenu/images/tree-node-open.gif';
                    }
                    else
                    {
                        con = getXMLRequester();
		                con.open('POST', 'index.php?mod=filebrowser&action=get_folder', true);
                        global_path = path;
                        global_name = name;

                        param = 'path='+escape(encodeURIComponent(path))+'&section_template='+escape(encodeURIComponent('{$section_template}'))+'&name='+escape(encodeURIComponent(name));

		                con.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		                con.setRequestHeader("Content-length", param.length);
                        con.onreadystatechange = handleFilebrowserGetResponse;
		                con.send(param);
		                con.close;
                        return false;
                    }
                }
	        }

	        /**
            * @desc Handle the response
            **/
	        function handleFilebrowserGetResponse()
            {
                // 4 equals ready and success
		        if (con.readyState == 4)
                {
			        var response = con.responseText;

                    document.getElementById('loading').style.display = 'none';

                    document.getElementById('section-' + global_name + '-' + global_path).style.display = 'block';
                    document.getElementById('section-' + global_name + '-' + global_path).innerHTML = response;
                    document.getElementById('node-' + global_name + '-' + global_path).src = '{$www_root_themes_core}/admin/adminmenu/images/tree-node-open.gif';

                    return true;
		        }
                else
                {
                    document.getElementById('loading').style.display = 'block';
                }
                return false;
            }
        </script>
    
{/move_to}
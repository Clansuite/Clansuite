<?php
# for localhost eyes only
if (false === in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')))
{
    header('HTTP/1.1 401 Access unauthorized');
    exit;
}

# defines
define('IN_PHP_CONSOLE', true);
define('PHP_CONSOLE_VERSION', '0.1-dev');

# errorlevel
ini_set('log_errors', 0);
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

# start output buffering
ob_start();

# exec command
if (empty($_GET['cmd']) === false)
{
    $ff = $_GET['cmd'];
    # keep in mind that there is a "disable_function" cmd in php.ini
    system($ff.' 2>&1');
}
else # show console page
{
    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <title>Clansuite Shell</title>
            <script type="text/javascript" language="javascript">

                    var CommHis = new Array();
                    var HisP;

                    function doReq(_1,_2,_3)
                    {
                        var XHR = false;

                        if(window.XMLHttpRequest)
                        {
                            XHR = new XMLHttpRequest();

                            if(XHR.overrideMimeType)
                            {
                                XHR.overrideMimeType("text/xml");
                            }
                        }
                        else
                        {
                            if(window.ActiveXObject)
                            {
                                try
                                {
                                    XHR = new ActiveXObject("Msxml2.XMLHTTP");
                                }
                                catch(e)
                                {
                                    try
                                    {
                                        XHR = new ActiveXObject("Microsoft.XMLHTTP");
                                    }
                                    catch(e)
                                    {}
                                }
                            }
                        }

                        if(!XHR)
                        {
                            return false;
                        }

                        XHR.onreadystatechange = function()
                        {
                            if(XHR.readyState == 4)
                            {
                                if(XHR.status == 200)
                                {
                                    if(_3)
                                    {
                                        eval(_2+"(XHR.responseXML)");
                                    }
                                    else
                                    {
                                        eval(_2+"(XHR.responseText)");
                                    }
                                }
                            }
                        };

                        XHR.open("GET",_1,true);
                        XHR.send(null);}

                    function pR(rS)
                    {
                        var _6 = document.getElementById("outt");
                        var _7 = rS.split("\n\n");
                        var _8 = document.getElementById("cmd").value;

                        _6.appendChild(document.createTextNode(_8));
                        _6.appendChild(document.createElement("br"));

                        for(var _9 in _7)
                        {
                            var _a = document.createElement("pre");

                            _a.style.display = "inline";
                            line = document.createTextNode(_7[_9]);
                            _a.appendChild(line);
                            _6.appendChild(_a);
                            _6.appendChild(document.createElement("br"));
                        }

                        _6.appendChild(document.createTextNode(":-> "));

                        _6.scrollTop = _6.scrollHeight;

                        document.getElementById("cmd").value="";
                    }
                    function keyE(_b)
                    {
                        switch(_b.keyCode)
                        {
                            case 13: // return
                                var _c = document.getElementById("cmd").value;
                                if(_c)
                                {
                                    CommHis[CommHis.length] = _c;
                                    HisP = CommHis.length;
                                    var _d = document.location.href+"?cmd="+escape(_c);
                                    doReq(_d,"pR");
                                }
                                break;
                            case 38: // up-arrow
                                if(HisP > 0)
                                {
                                    HisP--;
                                    document.getElementById("cmd").value = CommHis[HisP];
                                }
                                break;
                            case 40: // down-arrow
                                if(HisP < CommHis.length-1)
                                {
                                    HisP++;
                                    document.getElementById("cmd").value = CommHis[HisP];
                                }
                                break;
                            default:
                                break;
                        }
                    }
                </script>
        </head>
        <body style="font-family:courier;" onLoad="focus();console.cmd.focus()">
            <form onsubmit="return false" name="console"
                  style="color:#3F0; background:#000; position:relative; min-height:450px; max-height:490px">
                <-- Clansuite Command Prompt -->
                <br />
                Commands? Try "help" or "doctrine" for a start.
                <br />
                Keys? "Enter" obviously! "Up" and "Down" for cycling through the command history.
                <div id="outt"
                     style="color:#3F0; background:#000; overflow:auto; padding:5px; height:90%; min-height:450px; max-height:490px">
                    :-> <input tabindex="1" onkeyup="keyE(event)" style="color:#FFF;background:#333;width:100%;" id="cmd" type="text" />
                    
                    <span style="text-decoration: blink;">_</span>
                </div>
                
            </form>
        </body>
    </html>
<?php } ?>
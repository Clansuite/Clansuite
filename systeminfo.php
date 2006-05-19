<?php
/*****************************************************************************/
/* Clansuite - just another E-Sport CMS                                      */
/* Copyright (C) 1999 - 2006 Jens-André Koch (jakoch@web.de)                 */
/*                                                                           */
/* Clansuite is free software; you can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by      */
/* the Free Software Foundation; either version 2 of the License, or         */
/* (at your option) any later version.                                       */
/*                                                                           */
/* Clansuite is distributed in the hope that it will be useful,              */
/* but WITHOUT ANY WARRANTY; without even the implied warranty of            */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             */
/* GNU General Public License for more details.                              */
/*                                                                           */
/* You should have received a copy of the GNU General Public License         */
/* along with this program; if not, write to the Free Software               */
/* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA  */
/*****************************************************************************/

require 'shared/prepend.php';
include_header('System Information');
?>
<script src="shared/toc.js" type="text/javascript"></script>
<div id="body">
    <h1>System Information</h1>
	 <h2>Server</h2>

    <div class="topleftdiv">
    
    <?php
        echo '<img src="' . $_SERVER['PHP_SELF'] . '?=' . php_logo_guid() . '" border=\"0\"alt="PHP Logo !" />';
         echo 'PHP-Version :'.PHP_VERSION."<br />\n";	
			echo 'running on : '.PHP_OS."<br />\n";;
			echo 'Server Name '.getenv('SERVER_NAME')."<br />\n";
			echo 'SERVER_SOFTWARE: '.getenv('SERVER_SOFTWARE')."<br />\n";
			//echo"<img src=\"", $_SERVER['PHP_SELF'],  "?=",  mysql_logo_guid(), "\" border=\"0\" alt=\"Mysql Logo\">"; 
			echo "MySQL-Version: ",     mysql_get_client_info()."<br />\n";
			echo"<img src=\"", $_SERVER['PHP_SELF'],  "?=",  zend_logo_guid(), "\" border=\"0\" alt=\"Zend Logo\">"; 
			echo "Zend engine version: " . zend_version()."<br />\n";
			echo 'GATEWAY_INTERFACE: '.getenv('GATEWAY_INTERFACE')."<br />\n";
			$inter_type = php_sapi_name();
			if ($inter_type == "cgi")
			   print "Sie benutzen CGI PHP\n";
			else
			   print "Sie benutzen nicht CGI PHP\n"; 
			
			echo "<br />__FILE__ gives " .      __FILE__ . "<br>" ;
		   echo "__LINE__ gives " .      __LINE__ . "<br>" ;
		   echo "PHP_OS gives " .        PHP_OS . "<br>" ;
		   echo "TRUE gives " .          TRUE . "<br>" ;
		   echo "FALSE gives " .         FALSE . "<br>" ;
		   echo "E_ERROR gives " .       E_ERROR . "<br>" ;
		   echo "E_WARNING gives " .     E_WARNING . "<br>" ;
		   echo "E_PARSE gives " .       E_PARSE . "<br>" ;
		   echo "E_NOTICE gives " .      E_NOTICE . "<br>" ;
		   echo "E_ALL gives " .         E_ALL . "<br>" ;
		   
		   echo "</div><br><hr><br>" ;

   echo "SERVER_PROTOCOL gives "       . $SERVER_PROTOCOL      . "<br>" ;
   echo "REQUEST_METHOD gives "        . $REQUEST_METHOD       . "<br>" ;
   echo "QUERY_STRING gives "          . $QUERY_STRING         . "<br>" ;
   echo "DOCUMENT_ROOT gives "         . $DOCUMENT_ROOT        . "<br>" ;
   echo "HTTP_ACCEPT gives "           . $HTTP_ACCEPT          . "<br>" ;
   echo "HTTP_ACCEPT_CHARSET gives "   . $HTTP_ACCEPT_CHARSET  . "<br>" ;
   echo "HTTP_ENCODING gives"          . $HTTP_ENCODING        . "<br>" ;
   echo "HTTP_ACCEPT_LANGUAGE gives "  . $HTTP_ACCEPT_LANGUAGE . "<br>" ;
   echo "HTTP_CONNECTION gives "       . $HTTP_CONNECTION      . "<br>" ;
   echo "HTTP_HOST gives "             . $HTTP_HOST            . "<br>" ;
   echo "HTTP_REFERER gives "          . $HTTP_REFERER         . "<br>" ;
   echo "HTTP_USER_AGENT gives "       . $HTTP_USER_AGENT      . "<br>" ;
   echo "REMOTE_ADDR gives "           . $REMOTE_ADDR          . "<br>" ;
   echo "REMOTE_PORT gives "           . $REMOTE_PORT          . "<br>" ;
   echo "SCRIPT_FILENAME gives "       . $SCRIPT_FILENAME      . "<br>" ;
   echo "SERVER_ADMIN gives "          . $SERVER_ADMIN         . "<br>" ;
   echo "SERVER_PORT gives "           . $SERVER_PORT          . "<br>" ;
   echo "SERVER_SIGNATURE gives "      . $SERVER_SIGNATURE     . "<br>" ;
   echo "PATH_TRANSLATED gives "       . $PATH_TRANSLATED      . "<br>" ;
   echo "SCRIPT_NAME gives "           . $SCRIPT_NAME          . "<br>" ;
   echo "REQUEST_URI gives "           . $REQUEST_URI          . "<br>" ;

   echo "PHP_SELF gives "              . $PHP_SELF             . "<br>" ;
   echo "HTTP_COOKIE_VARS gives "      . $HTTP_COOKIE_VARS     . "<br>" ;
   echo "HTTP_GET_VARS gives "         . $HTTP_GET_VARS        . "<br>" ;
   echo "HTTP_POST_VARS gives "        . $HTTP_POST_VARS       . "<br>" ;
   
   echo "<br><hr>session data<br>" ;
    print "<BR>CGI: ".getenv(GATEWAY_INTERFACE);
print "<BR>Server: ".getenv(SERVER_NAME);
print "<BR>Software ".getenv(SERVER_SOFTWARE);
print "<BR>Protocol ".getenv(SERVER_PROTOCOL);
print "<BR>Request ".getenv(REQUEST_METHOD);
print "<BR>Query ".getenv(QUERY_STRING);
print "<BR>Doc ".getenv(DOCUMENT_ROOT);
print "<BR>Remote IP ".getenv(REMOTE_ADDR);
print "<BR>Remote port ".getenv(REMOTE_PORT);
print "<BR>Sever port ".getenv(SERVER_PORT);
print "<BR>Remote agent ".getenv(HTTP_USER_AGENT);
print "<BR>URI ".getenv(REQUEST_URI);

echo "<br><hr><h2>php extensions</h2> <br>" ;
$GeladeneExtensions = get_loaded_extensions();
for($i=0; $i<count($GeladeneExtensions); $i++)
 {
     echo$i+1,
         ": ",
         $GeladeneExtensions[$i],
         "<br>";
 }

echo "<br><hr>OS <br>" ;

echo php_uname();
echo getenv(DIRECTORY_SEPARATOR);
echo getenv(PHP_SHLIB_SUFFIX);   
echo getenv(PATH_SEPARATOR);   

echo "<br>" ;
#$dat = getrusage();
#echo $dat["ru_nswap"]. "<br>" ;        # number of swaps
#echo $dat["ru_majflt"]. "<br>" ;        # number of page faults
#echo $dat["ru_utime.tv_sec"]. "<br>" ;  # user time used (seconds)
#echo $dat["ru_utime.tv_usec"]. "<br>" ; # user time used (microseconds)  

echo "Last modified: ".date ("F d Y H:i:s.", getlastmod());
			?>
    </p></div>
<?php
include_footer();
?>
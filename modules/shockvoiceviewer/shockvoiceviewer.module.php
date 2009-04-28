<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: index.module.php 2625 2008-12-09 00:04:43Z vain $
    */

// Security Handler
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Module - Shockvoiceviewer
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 * @version    0.1
 */
class Module_Shockvoiceviewer extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    # holds the xml parser object
    private $parser;

    # parsing depth (tree)
    private $depth = 0;

    # html output container
    private $shockvoice_output = '';

    /**
     * Module_Shockvoiceviewer->execute()
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        #$this->moduleconfig = $this->getModuleConfig();
    }

    /**
     * startElement is a Callback of xml_set_element_handler()
     *
     * @param parser
     * @param name
     * @param attributes
     */
    private function startElement($parser, $name, $attrs)
    {
        #$this->shockvoice_output .= "&nbsp;&nbsp;&nbsp;&nbsp;";

	    $name_attr = "?";
	    $status_attr = "online";
	    $passwd_attr = "False";
	    $type_attr = "1";

	    if (count($attrs))
	    {
            foreach ($attrs as $k => $v)
            {
                if ($k == "NAME")
				    $name_attr = $v;
			    else if ($k == "TYPE")
				    $type_attr = $v;
			    else if ($k == "PASSWORD")
				    $passwd_attr = $v;
			    else if ($k == "STATUS")
				    $status_attr = $v;
            }
        }

	    if ($name == 'CHANNEL')
	    {
		    $img = '';

		    if ($type_attr == "0")
		    {
			    $img = "channel_temp";
		    }
		    else if ($type_attr == "1")
		    {
			    $img = "channel";
		    }
		    else if ($type_attr == "2")
		    {
			    $img = "channel_admin";
		    }

		    if ($passwd_attr == "True")
		    {
			    $img .= "_locked";
		    }

		    $this->shockvoice_output .= sprintf('<img src="%s/channel/%s.png" border="0">&nbsp;%s<br>',
		                                        WWW_ROOT.'/modules//shockvoiceviewer/images', $img, $name_attr);
	    }

	    if ($name == 'USER')
	    {
		    $img = '';
		    switch ((int)$status_attr)
		    {
    		    case 0: $img = "online"; break;
    		    case 1: $img = "away"; break;
    		    case 2: $img = "notavailable"; break;
    		    case 3: $img = "occupied"; break;
    		    case 4: $img = "donotdisturb"; break;
    		    case 5: $img = "freeforchat"; break;
    		    case 6: $img = "onthephone"; break;
    		    case 7: $img = "outtolunch"; break;
		    }
   		     $this->shockvoice_output .= sprintf('<img src="%s/status/%s.png" border="0">&nbsp;$s<br>',
   		                                WWW_ROOT.'/modules/shockvoiceviewer/images', $img, $name_attr);

	    }
    }

    /**
     * endElement is a Callback of xml_set_element_handler()
     *
     * @param parser
     * @param name
     */
    private function endElement($parser, $name)
    {
        # nothing to do
    }

    /**
     * Widget Shockvoiceviewer
     *
     * @todo set serverdata to configfile
     */
    public function widget_shockvoiceviewer()
    {
        # set modulename, because outside this widget a different module could be active
        $modulename = 'shockvoiceviewer';
        # insert the modulename to construct a configfilename and fetch it
        $this->getModuleConfig(ROOT_MOD.$modulename.DS.$modulename.'.config.php');

        # insert the values from moduleconfig to fetch the XML of the server
        $xmldata = file_get_contents("http://" . $this->getConfigValue('hostname', 'druckwelle-hq.de') . ":" . $this->getConfigValue('port', '8010') . "/" . $this->getConfigValue('serverid', '1') );

        # parse XML
        $this->parser = xml_parser_create(); #'UTF-8'

        # set this class to the parser
        xml_set_object($this->parser, $this);

        # let the parsing begin, and give them a start and end callback
        xml_set_element_handler($this->parser, "startElement", "endElement");

        # finally get the parsed output
        $this->shockvoice_output .= xml_parse($this->parser, $xmldata, true);

        # if there is nothing to output
        if ($this->shockvoice_output{1} == '')
        {
            # assemble errormessage
            $errormessage = sprintf("XML error: %s at line %d",
                                    xml_error_string(xml_get_error_code($this->parser)),
                                    xml_get_current_line_number($this->parser));

            # for output as an exception
            throw new Clansuite_Exception('Shockvoice Viewer reports: <br/>'.$errormessage );
        }

        # unset some data
        xml_parser_free($this->parser);

        #var_dump($this->shockvoice_output);

        # @todo cut off the mysterious 1 (where does this thingy come from?)
        $this->shockvoice_output = substr($this->shockvoice_output, 0, -1);

        # get the view
        $view = $this->getView();

        # assign data
        $view->assign('serverinfos_html', $this->shockvoice_output);
    }
}
?>
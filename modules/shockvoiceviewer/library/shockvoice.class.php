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
    * @link       http://gna.org/projects/
    *
    * @version    SVN: $Id$
    */

/**
 * Clansuite_Shockvoice_Query
 *
 * Purpose: queries a shockvoice server and displayes users and channels
 *
 * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 * @version    0.2
 */
class Clansuite_Shockvoice_Query
{
    # holds the xml parser object
    private $parser;

    # current channel
    private $current_channel = 0;

    # array of channels and users
    protected $shockvoice = array();

	/**
	 * Constructor
	 *
	 * @param	string	    $host
	 * @param	int			$port
	 * @param	int			$query_port
	 * @param	int			$server
	 * @param   string      $encoding
	 */
	public function __construct($host = 'localhost', $port = 8040, $query_port = 8010, $server = 1, $encoding = 'UTF-8')
	{
		$filename = "http://" . $host. ":" . $port . "/" . $server;

        $this->shockvoice['servername'] = $host;
        $this->shockvoice['port']       = $port;

        $this->query($filename, $encoding);
	}

    /**
     * Query the Shockvoice Server via CURL
     */
    public function query($filename, $encoding)
    {
        # this is buggy and doesn't fetch the whole file-length
        #xmldata = file_get_contents($filename);

        // Use cURL to get the RSS feed into a PHP string variable.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $filename);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $xmldata = curl_exec($ch);
        curl_close($ch);

        # hmm, append missing close-tag
        $xmldata .= '</sv>';

        #clansuite_xdebug::printr($xmldata);

        # parse XML
        $this->parser = xml_parser_create($encoding);

        # set this class to the parser
        xml_set_object($this->parser, $this);

        # let the parsing begin, and give them a start and end callback
        xml_set_element_handler($this->parser, "startElement", "endElement");

        # finally get the parsed output
        xml_parse($this->parser, $xmldata, true);

        # unset some data
        xml_parser_free($this->parser);

        #clansuite_xdebug::printr($this->shockvoice);

        # if there is nothing to output
        if (empty($this->shockvoice))
        {
            # assemble errormessage
            $errormessage = sprintf("XML error: %s at line %d",
                                    xml_error_string(xml_get_error_code($this->parser)),
                                    xml_get_current_line_number($this->parser));

            # for output as an exception
            throw new Clansuite_Exception('Shockvoice Viewer reports: <br/>'.$errormessage );
        }
    }

	/**
     * Getter Method for the Shockvoice Data Array
     *
	 * @return	array	array with keys 'channels' and 'users'
	 */
	public function getShockvoice()
	{
	    if(!empty($this->shockvoice['users']))
        {
            $this->shockvoice['num_clients']  = count($this->shockvoice['users']);
        }

        if(!empty($this->shockvoice['channels']))
        {
            $this->shockvoice['num_channels'] = count($this->shockvoice['channels']);
        }

        $this->shockvoice['request_ok']       = true;

        return $this->shockvoice;
	}

    /**
     * startElement is a callback of xml_set_element_handler()
     *
     * @param parser
     * @param name
     * @param attributes
     */
    private function startElement($parser, $name, $attributes)
    {
        $element = array(
                'name' => "?",
                'password' => false,
                'status' => 'online',
                'type' => '1',
                'id' => '0',
                'parentid' => '0'
        );

		if (count($attributes))
        {
            foreach ($attributes as $key => $value)
            {
                switch ($key)
                {
                    case 'NAME':
                        $element['name'] = $value;
                        break;
                    case 'PASSWORD':
                        $element['password'] = strtolower($value);
                        break;
                    case 'STATUS':
                        $element['status'] = $value;
                        break;
                    case 'TYPE':
                        $element['type'] = $value;
                        break;
                    case 'ID':
                        $element['id'] = $value;
                        break;
                    case 'PARENTID':
                        $element['parentid'] = $value;
                        break;
                }
                #clansuite_xdebug::printr($attributes);
            }
        }

		if ($name == 'CHANNEL')
        {
            $this->current_channel = $element['id'];

            # add current channel as a subchannel (of a parentchannel)
            if($element['parentid'] != 0)
            {
                $this->shockvoice['channels'][$element['parentid']]['subchannels'][$element['id']] = array(
                        'id' => $element['id'],
                        'parentid' => $element['parentid'],
                        'name' => $element['name'],
                        'password' => $element['password'],
                        'type' => $element['type'],
                        'image' => $this->getChannelImage($element['type'], $element['password'])
                );
            }
            else # it's a channel directly below root (parentchannel = 0)

            {
                $this->shockvoice['channels'][$element['id']] = array(
                        'id' => $element['id'],
                        'parentid' => $element['parentid'],
                        'name' => $element['name'],
                        'password' => $element['password'],
                        'type' => $element['type'],
                        'image' => $this->getChannelImage($element['type'], $element['password'])
                );
            }
            #clansuite_xdebug::printr($this->shockvoice['channels']);
        }
        elseif ($name == 'USER')
        {
            $this->shockvoice['users'][$element['id']] = array(
                    'channelid' => $this->current_channel,
                    'id' => $element['id'],
                    'name' => $element['name'],
                    'password' => $element['password'],
                    'status' => $element['status'],
                    'image' => $this->getUserImage($element['status'])
            );
            #clansuite_xdebug::printr($this->shockvoice['users']);
        }
    }

    /**
     * endElement is a callback of xml_set_element_handler()
     *
     * @param parser
     * @param name
     */
    private function endElement($parser, $name)
    {
        /*if($name == 'CHANNEL')
        {
			if ($this->current_channel != 0)
			{
				$this->current_channel = $this->shockvoice['channels'][$this->current_channel]['parentid'];
		    }
		}*/
    }

    /**
     * Return a channel image name (viewhelper for channel images)
     *
     * @param $type type of channel
     * @param $password
     * @return $img html string
     */
    public function getChannelImage($type, $password)
    {
        $img = 'channel';

        if ($type == "0")
        {
            $img = "temp";
        }
        elseif ($type == "1")
        {
            $img = "channel";
        }
        elseif ($type == "2")
        {
            $img = "channel_admin";
        }

        if ($password == 'true')
        {
            $img .= "_locked";
        }

        $img = sprintf('<img src="%s/channel/%s.png" border="0">',
                WWW_ROOT.'/modules/shockvoiceviewer/images',
                $img);

        return $img;
    }

    /**
     * Return a channel image name (viewhelper for user images)
     *
     * @param $status int shockvoice user status
     * @return $image html string
     */
    public function getUserImage($status)
    {
        $img = '';
        switch ((int) $status)
        {
            case 0: $img = "online";
                break;
            case 1: $img = "away";
                break;
            case 2: $img = "notavailable";
                break;
            case 3: $img = "occupied";
                break;
            case 4: $img = "donotdisturb";
                break;
            case 5: $img = "freeforchat";
                break;
            case 6: $img = "onthephone";
                break;
            case 7: $img = "outtolunch";
                break;
        }

        $img = sprintf('<img src="%s/status/%s.png" border="0">',
                WWW_ROOT.'/modules/shockvoiceviewer/images',
                $img);

        return $img;
    }
}
?>
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
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

# Load Clansuite_Renderer_Base
require dirname(__FILE__) . '/renderer.base.php';

/**
 * Clansuite Renderer Class - Renderer for CSV
 *
 * This is a wrapper/adapter for rendering CSV Data. CSV stands for 'comma-seperated-values'.
 * These files are commonly used to export and import data into different databases.
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Renderer
 */
class Clansuite_Renderer_CSV extends Clansuite_Renderer_Base
{
    private $data = array();

    function __construct(Phemto $injector = null, Clansuite_Config $config)
    {
        parent::__construct();
    }

    public function initializeEngine()
    {

    }

    public function configureEngine()
    {

    }

    public function render($filepath, $data = null, $header = array())
    {
        if($data === null)
        {
            $data = $this->data;
        }

        $this->mssafe_csv($filepath, $data, $header = array());
    }

    public function assign($data)
    {
        $this->data = $data;
    }

    /**
     * mssafe_csv() builds csv files readable by ms-excel/access.
     *
     * @author soapergem[at]gmail[dot]com
     * @link http://de.php.net/manual/de/function.fputcsv.php#90883
     */
    private function mssafe_csv($filepath, $data, $header = array())
    {
        if($fp = fopen($filepath, 'w'))
        {
            $show_header = true;

            if(empty($header))
            {
                $show_header = false;
                reset($data);
                $line = current($data);

                if(empty($line) == false)
                {
                    reset($line);
                    $first = current($line);

                    if(substr($first, 0, 2) == 'ID' and preg_match('/["\\s,]/', $first) == false)
                    {
                        array_shift($data);
                        array_shift($line);
                        if(empty($line) == true)
                        {
                            fwrite($fp, '"' . $first . '"' . "\r\n");
                        }
                        else
                        {
                            fwrite($fp, '"' . $first . '",');
                            fputcsv($fp, $line);
                            fseek($fp, -1, SEEK_CUR);
                            fwrite($fp, "\r\n");
                        }
                    }
                }
            }
            else
            {
                reset($header);
                $first = current($header);

                if(substr($first, 0, 2) == 'ID' and preg_match('/["\\s,]/', $first) == false)
                {
                    array_shift($header);

                    if(empty($header))
                    {
                        $show_header = false;
                        fwrite($fp, '"' . $first . '"' . "\r\n");
                    }
                    else
                    {
                        fwrite($fp, '"' . $first . '",');
                    }
                }
            }

            if($show_header)
            {
                fputcsv($fp, $header);
                fseek($fp, -1, SEEK_CUR);
                fwrite($fp, "\r\n");
            }

            foreach($data as $line)
            {
                fputcsv($fp, $line);
                fseek($fp, -1, SEEK_CUR);
                fwrite($fp, "\r\n");
            }
            fclose($fp);
        }
        else
        {
            return false;
        }
        return true;
    }
}
?>
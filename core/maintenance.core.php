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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id: xdebug.core.php 4866 2010-10-25 19:57:34Z vain $
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Maintenance
 *
 * @author Paul Brand <info@isp-tenerife.net>
 * @todo: Umstellen auf gettext
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Maintenance
 */
class Clansuite_Maintenance
{

    private static $language;
    private static $reason;
    private static $timeout;
    private static $filePath;
    private static $aText = array(
        '1' => array(
                'de' => array(
                        'title'         => 'Wartungsmodus',
                        'reason'     => 'Es werden Arbeiten an der Datenbankstruktur durchgef&uuml;hrt.',
                        'sorry'        => 'Bitte entschuldigen Sie die Unannehmlichkeiten.',
                        'back'        => 'Bitte besuchen Sie uns in ca. %d %s wieder.',
                        'min'          => 'Minuten',
                       ),
                'en' => array(
                        'title'         => 'Maintenance Mode',
                        'reason'     => 'SITE is currently undergoing scheduled maintenance.',
                        'sorry'        => 'Sorry for the inconvenience.',
                        'back'        => 'Please try back in %d %s.',
                        'min'          => 'minutes',
                       ),
             ),
        '2' => array(
                'de' => array(
                        'title'         => '',
                        'reason'     => '',
                       ),
                'en' => array(
                        'title'         => '',
                        'reason'     => '',
                       ),
             )
    );


    public function run( array $config, $filePath = null )
    {
        self::$language = $config['language']['default'];

        /*
         * read reason from $config
         */
        if( $config['mainteance']['reason'] == 0 )
        {
            self::$reason = 1;
        }
        else {
            self::$reason    = $config['mainteance']['reason'];
        }

        /*
         * read timeout from $config
         */
        if( $config['mainteance']['timeout'] == 0 )
        {
            self::$timeout   = 60;
        }
        else {
            self::$timeout    = $config['mainteance']['timeout'];
        }

        if( $filePath )
        {
            self::$filePath   = $filePath;
        }
        else {
            self::$filePath   = ROOT_THEMES_CORE . 'view/smarty/maintenance.tpl';
        }

        self::execute();
    }

    /**
     * output mainteance
     */
    public function execute()
    {
        $title      = self::$aText[self::$reason][self::$language]['title'];
        $reason = self::$aText[self::$reason][self::$language]['reason'];
        $sorry    = self::$aText[self::$reason][self::$language]['sorry'];
        $back    = sprintf( self::$aText[self::$reason][self::$language]['back'], self::$timeout, self::$aText[self::$reason][self::$language]['min'] );

        $content = file_get_contents( self::$filePath );

        $content = str_replace( '{title}', $title, $content );
        $content = str_replace( '{reason}', $reason, $content );
        $content = str_replace( '{back}', $back, $content );
        $content = str_replace( '{sorry}', $sorry, $content );

        echo $content;
    }

}
?>
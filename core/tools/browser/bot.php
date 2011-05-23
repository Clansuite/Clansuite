<?php

/** -------------------------------------------------
 * BOTS
 * -------------------------------------------------
 *  
 *  - search    = searchstring
 *  - type       = browser|bot
 *  
 */

$bot = array(

    'Googlebot' =>
        array (
            'search' =>
                array(
                    '/Googlebot\/([0-9a-z\+\-\.]+).*/si',
                    '/Googlebot\-(Image\/[0-9a-z\+\-\.]+).*/si',
                ),
            'type' => 'bot',
        ),

    'MSN Bot' =>
        array (
            'search' =>
                array(
                    '/msnbot(-media|)\/([0-9a-z\+\-\.]+).*/si',
                    '/msnbot\/([0-9a-z\+\-\.]+).*/si',
                ),
            'type' => 'bot',
        ),

);

?>
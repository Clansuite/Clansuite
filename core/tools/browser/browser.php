<?php

/** -------------------------------------------------
 * BROWSER
 * -------------------------------------------------
 *  
 *  - search    = searchstring
 *  - type       = browser|bot
 *  - engine    = browserengine
 *  - vparam   = version string name
 *  - eparam   = engine string name
 *  
 */

$browser = array(

    # Firefox
    'Firefox' =>
        array (
            'search' =>
                array (
                    0 => '/mozilla.*rv:[0-9\.]+.*gecko\/[0-9]+.*firefox\/([0-9a-z\+\-\.]+).*/si',
                ),
            'type' => 'browser',
            'engine' => 'gecko',
            'vparam' => 'firefox/',
            'eparam' => 'rv:',
        ),

    # Safari
    'Safari' =>
        array (
            'search' =>
                array (
                    0 => '/mozilla.*applewebkit.*safari\/([0-9a-z\+\-\.]+).*/si',
                ),
            'type' => 'browser',
            'engine' => 'webkit',
            'vparam' => 'version/',
            'eparam' => 'applewebkit/',
        ),

    # Google Chrome
    'Google Chrome' =>
        array (
            'search' =>
                array (
                    0 => '/\schrome/si',
                ),
            'type' => 'browser',
            'engine' => 'webkit',
            'vparam' => 'chrome/',
            'eparam' => 'applewebkit/',
        ),

    # Opera
    'Opera' =>
        array (
            'search' =>
                array (
                    0 => '/mozilla.*opera ([0-9a-z\+\-\.]+).*/si',
                    1 => '/^opera\/([0-9a-z\+\-\.]+).*/si',
                ),
            'type' => 'browser',
            'engine' => 'presto',
            'vparam' => 'version/',
            'eparam' => 'presto/',
        ),

    # Internet Explorer
    'Internet Explorer' =>
        array (
            'search' =>
                array (
                    0 => '/microsoft.*internet.*explorer/si',
                    1 => '/mozilla.*MSIE ([0-9a-z\+\-\.]+).*/si',
                ),
            'type' => 'browser',
            'engine' => 'trident',
            'vparam' => 'msie ',
            'eparam' => 'trident/',
        ),

    # Konqueror
    'Konqueror' =>
        array (
            'search' =>
                array (
                    0 => '/mozilla.*konqueror\/([0-9a-z\+\-\.]+).*/si',
                ),
            'type' => 'browser',
            'engine' => 'khtml',
            'vparam' => 'Konqueror/',
            'eparam' => 'KHTML/',
        ),

);

?>
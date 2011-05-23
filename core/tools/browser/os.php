<?php
/** -------------------------------------------------
 * OPERATING SYSTEM (OS)
 * -------------------------------------------------
 *  
 *  - search        = searchstring
 *  - subsearch   = sub search in search
 *  - addsearch   = additional searchstring for spezified os
 *  - type           = os
 *  
 */

$os = array(

    # Windows
    'Windows' =>
        array (
            'search' =>
                array (
                    0 => '/windows ([0-9\.]+).*/si',
                    1 => '/[ \(]win([0-9\.]+).*/si',
                    2 => '/windows (me)/si',
                    3 => '/windows (ce)/si',
                    4 => '/windows (xp)/si',
                    5 => '/windows (nt)/si',
                    6 => '/windows nt ([0-9\.]+).*/si',
                    7 => '/winnt([0-9\.]+).*/si',
                    8 => '/windows/si',
                ),
            'subsearch' =>
                array(
                    'Millenium' => '/me/si',
                    'CE' => '/ce/si',
                    'XP' => '/xp/si',
                    'NT' => '/nt/si',
                    '2000' => '/5.0/si',
                    '2000' => '/5.01/si',
                    'XP' => '/5.1/si',
                    'Server 2003' => '/5.2/si',
                    'Vista' => '/6.0/si',
                    '7' => '/6.1/si',
                ),
            'type' => 'os',
        ),

    'Linux' =>
        array (
            'search' => 
                array(
                    0 => '/linux/si',
                ),
            'addsearch' =>
                array(
                    'Mandrake' => '/mdk/si',
                    'Kanotix' => '/kanotix/si',
                    'Lycoris' => '/lycoris/si',
                    'Knoppix' => '/knoppix/si',
                    'CentOS' => '/centos/si',
                    'Gentoo' => '/gentoo/si',
                    'Fedora' => '/fedora/si',
                    'Ubuntu 7.04 Feisty Fawn' => '/ubuntu.feist/si',
                    'Ubuntu 6.10 Edgy Eft' => '/ubuntu.edgy/si',
                    'Ubuntu 6.06 LTS Dapper Drake' => '/ubuntu.dapper/si',
                    'Ubuntu 5.10 Breezy Badger' => '/ubuntu.breezy/si',
                    'Kubuntu' => '/kubuntu/si',
                    'Xubuntu' => '/xubuntu/si',
                    'Ubuntu' => '/ubuntu/si',
                    'Slackware' => '/slackware/si',
                    'Suse' => '/suse/si',
                    'Redhat' => '/redhat/si',
                    'Debian' => '/debian/si',
                    'PLD' => '/PLD\//si',
                ),
            'type' => 'os',
        ),

    'BSD' =>
        array (
            'search' =>
                array(
                    0 => '/bsd/si',
                ),
            'addsearch' =>
                array(
                    'FreeBSD'=>'/freebsd/si',
                    'OpenBSD'=>'/openbsd/si',
                    'NetBSD'=>'/netbsd/si',
                ),
            'type' => 'os',
        ),

    'Mac OS' =>
        array (
            'search' =>
                array (
                    0 => '/mac_/si',
                    1 => '/macos/si',
                    2 => '/powerpc/si',
                    3 => '/mac os/si',
                    4 => '/68k/si',
                    5 => '/macintosh/si',
                ),
            'type' => 'os',
        ),

    'SunOs' =>
        array (
            'search' =>
                array(
                    0 => '/sunos/si',
                ),
            'type' => 'os',
        ),

);

?>
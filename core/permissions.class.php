<?php
/**
 * permissions management
 *
 * PHP versions 5.1.4
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
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @author     Florian Wolf <xsign.dll@clansuite.com>
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  2006 Clansuite Group
 * @license    see COPYING.txt
 * @version    SVN: $Id $
 * @link       http://gna.org/projects/clansuite
 * @since      File available since Release 0.1
 */

//----------------------------------------------------------------
// Security Handler
//----------------------------------------------------------------
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

class permissions {
    
    /** Permissions Tabellen-Übersicht
      *
      *
      *                    (3)                   (4)
      * cs_users -> [R] cs_user_usergroups -> cs_usergroup
      *                                             |
      *    |                                  (5)   | 
      *    |                           [R] cs_usergroup_rights
      *    |                                        |
      *    |               (1)                  (2) |
      *    +------> [R] cs_user_rights     -> cs_rights
      *
      * Jeweilige Nummer gibt die Position in der Class an
      */
   
    
    /**
      * A. User -> Recht
      *    Tabellen:
      *     cs_users -> [R] cs_user_rights -> cs_rights
      *
      *     get_rights_by_userid( $user_id )
      *
      * B. User -> Benutzergruppe
      *    Tabellen:
      *     cs_users -> [R] cs_user_usergroups -> cs_usergroup
      *
      *    get_usergroups_by_userid( $user_id )
      *
      * C. Benutzergruppe -> Rechte
      *    Tabellen:
      *     cs_usergroup -> [R] cs_usergroup_rights -> cs_rights       
      *
      *    get_rights_by_groupid ( $usergroup_id )
      *
      * D. [TODO] Sonderfälle:
      *    a. Implizite Rechte
      *    b. Roles
      *    c. Areas
      */
    
    
     /** =================================================
      * 1. Verwaltung der Zuordnung User-zu-Recht 
      * Relations-Tabelle: cs_user_rights
      * =================================================
         
       CREATE TABLE `cs_user_rights` (
          `user_id` int(10) unsigned NOT NULL default '0',
          `right_id` int(5) unsigned NOT NULL default '0',
          PRIMARY KEY  (`user_id`,`right_id`)
        ) ENGINE=MyISAM;
        
      * =================================================
      */
    
    // Ordnet dem User ein Recht zu 
    function add_user_right( $user_id, $right_id )
    {
        }
    
    // Löscht ein Recht beim User
    function delete_user_right( $user_id, $right_id )
    {
        }
        
    
    /** ==================================================
      * 2. Verwaltung von Rechten 
      * Tabelle: cs_rights
      * ==================================================
     
     
        CREATE TABLE `cs_rights` (
        `right_id` int(11) unsigned NOT NULL default '0',
        `right_name` varchar(150) NOT NULL default '',
        PRIMARY KEY  (`right_id`)
        ) ENGINE=MyISAM;
    
      */
      
    // Ein Recht hinzufügen
    function add_right( $right_name )
    {
        }
    
    // Ein Recht modifizieren
    function modify_right( $right_id )
    {
        }
    
    // Ein Recht löschen
    function delete_right( $right_id )
    {
        }
    
    /** Abfrage von Rechten
     * a. über die right_id
     * b. über den right_name
     */
    function get_right( $right_id = NULL, $right_name = NULL )
    {
        }
    
    
    /**
      * Abfrage aller direkt zugewiesenen Rechte der user_id!
      *
      * @param $user_id
      * @return $rights
      */
    function get_rights_by_userid( $user_id )
    {
        }     
    
   /** =================================================
      * 3. Verwaltung der Zuordung von User zu Usergroup
      * Relations-Tabelle: cs_user_usergroups
      * =================================================
        
       CREATE TABLE `cs_user_usergroups` (
         `user_id` int(10) unsigned NOT NULL default '0',
         `usergroup_id` int(5) unsigned NOT NULL default '0',
         PRIMARY KEY  (`user_id`,`group_id`)
       ) ENGINE=MyISAM;
    
      * =================================================
      */
    
    // Zuordung eines Users zu einer Benutzergruppe
    function add_user_to_usergroup( $user_id, $usergroup_id )
    {
        }
    
    // Modifizieren der Zuordnung des Users zu einer Benutzergruppe
    function modify_user_to_usergroup( $user_id, $usergroup_id )
    {
        }
    
    // Löschen der Zuordnung des Users zu einer Benutzergruppe
    function delete_user_from_usergroup( $user_id, $usergroup_id ){
        }

    
    /**
     * Abfrage ermittelt alle Benutzergruppen des Users.
     *
     * @param $user_id
     * @return $groups
     */
    function get_usergroups_by_userid( $user_id ){}
   
   
   /** ==================================================
     * 4. Verwaltung von Usergruppen 
     * Tabelle: cs_usergroup
     * ==================================================
     
       CREATE TABLE `cs_usergroup` (
          `usergroup_id` int(5) unsigned NOT NULL auto_increment,
          `usergroup_pos` tinyint(4) unsigned NOT NULL default '1',
          `usergroup_name` varchar(75) default NULL,
          PRIMARY KEY  (`group_id`)
        ) ENGINE=MyISAM;
     
     * ==================================================
     */
    
    
    // Hinzufügen einer Gruppe
    function add_usergroup( $usergroup_pos, $usergroup_name )
    {
        }
    
    /** 
     * Modifizieren einer Gruppe
     * a. Verschieben der Posi
     * b. Umbenennen einer Gruppe
     */    
    function modify_usergroup( $usergroup_id, $usergroup_pos, $usergroup_name )
    {
        }
        
    // Löschen einer Gruppe
    function delete_usergroup()
    {
        }
    
  
    /** =================================================
      * 5. Verwaltung der Zuordung von Usergroups zu Rights
      * Relations-Tabelle: cs_usergroup_rights
      * =================================================
       
        CREATE TABLE `cs_usergroup_rights` (
          `usergroup_id` int(5) unsigned zerofill NOT NULL default '00000',
          `right_id` int(5) unsigned zerofill NOT NULL default '00000',
          `right_pos` tinyint(4) NOT NULL default '1',
          PRIMARY KEY  (`group_id`,`right_id`)
        ) ENGINE=MyISAM;

      * =================================================
      */
    
    // Zuordung von Rechten zu einer Gruppe
    function add_usergroup_rights( $right_id, $usergroup_id )
    {
        }
    
    /** 
     * Modifizieren einer Zuordnung
     * a. Verschieben der Posi
     */    
    function modify_usergroup_rights( $right_id, $right_pos )
    {
        }
        
    // Löschen von Rechten aus einer Gruppe
    function delete_usergroup_rights()
    {
        }
     
    /**
     * Ermittelt alle Rechte einer Benutzergruppe.
     * Abfrage von group_id | group_name | right_id | right_name
     * 
     * @param $user_id
     * @return $allrights;
     */
     function get_rights_by_usergroupid( $usergroup_id ) {}
       
    
}
?>
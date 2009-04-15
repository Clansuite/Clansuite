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
    * @version    SVN: $Id: get_user.filter.php 2614 2008-12-05 21:18:45Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' ); }

/**
 * Clansuite Filter - Permissions / RBACL
 *
 * Purpose: Perform an Permissions / RBACL Check
 *
 * @package clansuite
 * @subpackage filters
 * @implements FilterInterface
 */
class permissions implements Clansuite_FilterInterface
{
    private $user    = null;
    private $rbacl   = null;

    public function __construct(Clansuite_User $user)
    {
        $this->user = $user;
        $rbacl = Clansuite_RBACL::getInstance();
    }

    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        if (!$rbacl->isAuthorized($actionname, $this->user->getUserId()))
        {   
            # @todo errorpage, no permission to perform this action. access denied.
			$response->redirect(); 
		}
    }
}
?>
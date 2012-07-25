<?php defined('IN_CS') or exit('Direct Access forbidden.');
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
    * http://www.clansuite.com/
    *
    * GNU/GPL v2 or any later version; see LICENSE file
    *
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    */

namespace Clansuite\Module;

/**
 * Clansuite_Module_Cronjobs_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Cronjobs
 */
class Cronjobs_Admin extends Controller
{
    public function _initializeModule()
    {

    }

    public function action_admin_list()
    {
        # Applying a Layout Template
        $view = $this->getView()->setLayoutTemplate('index.tpl');

        $cronjobs = array();

        $this->getView()->assign('cronjobs', $cronjobs);

        $this->display();
    }
}
?>
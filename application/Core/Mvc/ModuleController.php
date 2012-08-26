<?php
namespace Clansuite\application\Core\Mvc;

class ModuleController extends \Koch\Module\Controller
{
    /**
     * Proxy/convenience method: returns the Clansuite Configuration as array
     *
     * @return $array Clansuite Main Configuration (/configuration/clansuite.config.php)
     */
    public function getClansuiteConfig()
    {
        return $this->config = \Clansuite\Application::getClansuiteConfig();
    }
}

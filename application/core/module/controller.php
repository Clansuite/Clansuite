<?php
namespace Clansuite\Module;

class Controller extends \Koch\Module\Controller
{
    /**
     * Proxy/convenience method: returns the Clansuite Configuration as array
     *
     * @return $array Clansuite Main Configuration (/configuration/clansuite.config.php)
     */
    public function getClansuiteConfig()
    {
        return $this->config = Clansuite\CMS::getClansuiteConfig();
    }
}
?>
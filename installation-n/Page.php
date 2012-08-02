<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Clansuite\installation;

class Page
{
    public $values;
    public $step;
    public $total_steps;
    public $error;
    public $language;

    public function __construct($language, $step, $total_steps, $error = '')
    {
        $this->language = $language;
        $this->step = $step;
        $this->total_steps = $total_steps;
        $this->error = $error;
    }

    public function render()
    {
        /**
         * Fetch class variables into the local scope.
         * The just seem to be unused, in fact they are used by the included files.
         */
        $language       = $this->language;
        $error          = $this->error;
        $step           = $this->step;
        $total_steps    = $this->total_steps;

        if (method_exists($this, 'getDefaultValues')) {
            $values = $this->getDefaultValues();
        }

        if (DEBUG == false) {
            ob_start();
        }

        include INSTALLATION_ROOT . 'view/header.php';
        include INSTALLATION_ROOT . 'view/sidebar.php';
        include INSTALLATION_ROOT . 'view/step' . $step . '.php';
        include INSTALLATION_ROOT . 'view/footer.php';

        if (DEBUG == false) {
            ob_get_flush();
        }
    }

    public function setStep($step)
    {
        $this->step = $step;
    }

    public function setValues($values)
    {
        $this->values = $values;
    }

    public function setErrorMessage($error)
    {
        // if we already have an error message, then append the next one
        if ($this->error != '') {
            $this->error .= $error;
        } else {
            $this->error = $error;
        }
    }

    public function getErrorMessage($error)
    {
        $this->error = $error;
    }

    public function setTotalSteps($total_steps)
    {
        $this->total_steps = $total_steps;
    }
}

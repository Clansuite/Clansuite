<?php

namespace Clansuite\Installation\Controller;

class InstallationStepBase
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

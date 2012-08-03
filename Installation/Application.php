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

namespace Clansuite\Installation;

/**
 * Clansuite Installation Application
 * ----------------------------------
 *
 * GET Requests
 * ------------
 * "?reset_session"      - Resets the installaton session
 * "?delete_installaton" - Deletes the Installation Folder
 */
class Application
{
    /**
     * @var string Language Locale (german, english).
     */
    public $locale;

    /**
     * @var object Language.
     */
    public $language;

    /**
     * @var int The current installation step.
     */
    public $step;

    /**
     * @var int The total number of steps, dynamically calculated.
     */
    public $total_steps;

    /**
     * @var string Errormessage.
     */
    public $error;

    /**
     * @var array Default Values for the formulars.
     */
    public $values;

    public function __construct()
    {
        $this->handleRequest_deleteInstallationFolder();

        $this->loadDoctrine();

        $this->handleRequest_Language();
        $this->loadLanguage();

        $this->getTotalNumberOfInstallationSteps();

        $this->determineCurrentStep();
        $this->processPreviousStep();
        $this->calculateInstallationProgress();
        $this->renderStep();

        register_shutdown_function(array($this, 'shutdown'));
    }

    public function loadDoctrine()
    {
        // Initialize Doctrine2 Autoloader
        require ROOT . 'libraries/Doctrine/Common/ClassLoader.php';
        $classLoader = new \Doctrine\Common\ClassLoader('Doctrine', realpath(ROOT . 'libraries/'));
        $classLoader->register();

        // Register "Doctrine Extensions" with the Autoloader
        $classLoader = new \Doctrine\Common\ClassLoader('DoctrineExtensions', realpath(ROOT . 'libraries/'));
        $classLoader->register();
    }

    /**
     * Triggers the self deletion of the installation folder.
     *
     * When the installation finishes in step 7, the user is asked,
     * wether he might delete the installation folder for security purposes.
     */
    public function handleRequest_deleteInstallationFolder()
    {
        // allow session reset only in debug mode
        if (DEBUG == true and isset($_GET['reset_session'])) {
            $_SESSION = array();
            unset($_SESSION);
        }

        if (isset($_GET['delete_installation'])) {
            /**
             * Delete the installation folder
             */
            echo "Deleting Directory - " . __DIR__;

            \Clansuite\Installation\Helper::removeDirectory(__DIR__);

            // display success message
            if (false === file_exists(__DIR__)) {
                echo '<p>
                        <center><h1>Finished!</h1><br />
                            <p><a href="../index.php">Click here to proceed!</a></p>
                        </center>
                      </p>';
            }

            exit();
        }
    }

    /**
     * Sets the requested Locale.
     *
     * Processing order is GET before SESSION and Default.
     * This allows changing the language during the installation.
     * If session is empty or Step 1 then use DEFAULT.
     */
    public function handleRequest_Language()
    {
        date_default_timezone_set('Europe/Berlin');

        // Get language from GET
        if (isset($_GET['lang']) and empty($_GET['lang']) === false) {
            $this->locale = (string) htmlspecialchars($_GET['lang'], ENT_QUOTES, 'UTF-8');
        } else {
            // Get language from SESSION
            if (isset($_SESSION['lang'])) {
                $this->locale = $_SESSION['lang'];
            }

            // SET DEFAULT locale
            if ($this->step == 1 or empty($_SESSION['lang'])) {
                $this->locale = 'german';
            }
        }
    }

    public function loadLanguage()
    {
        /**
         * Load Language File
         */
        try {
            $file = INSTALLATION_ROOT . 'languages' . DIRECTORY_SEPARATOR . $this->locale . '.install.php';

            if (is_file($file) === true) {
                include_once $file;

                $classname = '\Clansuite\Installation\Language\\' . $this->locale;
                $this->language = new $classname;

                $_SESSION['lang'] = $this->locale;
            } else {
                throw new \Clansuite\Installation_Exception('<span style="color:red">Language file missing: <strong>' . $file . '</strong></span>');
            }
        } catch (\Exception $e) {
            exit($e);
        }
    }

    /**
     * Returns the total number of installations steps
     * by counting the number of classes named "\Clansuite\Installation_StepX".
     *
     * @return int Total number of install steps. $_SESSION['total_steps']
     */
    public function getTotalNumberOfInstallationSteps()
    {
        // count the files only once
        if (isset($_SESSION['total_steps']) and $_SESSION['total_steps'] > 0) {
            $this->total_steps = $_SESSION['total_steps'];
        } else {
            // get array with all installaton step files
            $step_files = glob('Steps/Step*.php');

            // count the number of files named "stepX"
            $_SESSION['total_steps'] = count($step_files);

            $this->total_steps = $_SESSION['total_steps'];
        }

        return $this->total_steps;
    }

    /**
     * Handles Installation Steps and Calculates Installation Progress Bar
     *
     * Procedure Notice:
     * If a STEP is successful,
     * proceed to the next, else return to the same STEP and display error.
     */
    public function determineCurrentStep()
    {
        // update the session with the given variables!
        $_SESSION = \Clansuite\Installation\Helper::array_merge_rec($_POST, $_SESSION);

        /**
         * STEP HANDLING
         */
        if (isset($_SESSION['step']) === true) {
            $this->step = intval($_SESSION['step']);

            if ( isset($_POST['step_forward']) === true and ($this->step == $_POST['submitted_step'])) {
                $this->step = $this->step + 1;
            }

            if (isset($_POST['step_backward']) === true and ($this->step == $_POST['submitted_step'])) {
                $this->step = $this->step - 1;
            }
        } else {
            $this->step = 1;
        }

        if ($this->step >= $this->total_steps) {
            $this->step = $this->total_steps;
        }

        if ($this->step == 0) {
            $this->step = 1;
        }

        // remove not needed values
        unset($_SESSION['step_forward'], $_SESSION['step_backward'], $_SESSION['submitted_step']);
    }

    public function calculateInstallationProgress()
    {
        /**
         * Calculate Progress Percentage
         */
        $_SESSION['progress'] = \Clansuite\Installation\Helper::calculateProgress($this->step, $this->total_steps);
    }

    public function processPreviousStep()
    {
        // there isn't a controller before step 1
        if ($this->step == 1) {
            return;
        }

        $previous_step = $this->step - 1;

        $prev_step_class = '\Clansuite\Installation\Step' . $previous_step;

        if (class_exists($prev_step_class)) {
            $prev_step = new $prev_step_class(
                $this->language,
                $this->step,
                $this->total_steps,
                $this->error
            );

            if (false === method_exists($prev_step, 'validateFormValues')) {
                /**
                 * We just finished an installation step without sending any form values.
                 * Steps 1, 2, 3.
                 */

                return;
            } else {
                /**
                 * We finished an installation step with sending form values.
                 * Steps 4, 5, 6.
                 */

                // The incomming form values must be valid.
                if ($prev_step->validateFormValues() === true) {
                    // The form values are valid.
                    // Now process them.
                    $prev_step->processValues();
                }

                /**
                 * let's test if an errormessage was set during
                 * validateFormValues() and processValues()
                 */
                if ($prev_step->error != '') {
                    $this->error = $prev_step->error;
                    $this->step = $previous_step;
                }
            }
        }
    }

    public function renderStep()
    {
        /**
         * =========================================================
         *      SWITCH to the next Installation STEP
         * =========================================================
         */

        $step_class = '\Clansuite\Installation\Steps\Step' . $this->step;

        if (class_exists($step_class)) {
           $_SESSION['step'] = $this->step;

           $step = new $step_class(
                $this->language,
                $this->step,
                $this->total_steps,
                $this->error
           );

           $step->render();
        }
    }

    public static function shutdown()
    {
        if (true == session_id()) {
            // Save + close the session
            session_write_close();
        }
    }
}

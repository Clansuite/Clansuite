<?php

// Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}


/**
 * Step 5 - Website Configuration
 */
class Clansuite_Installation_Step5 extends Clansuite_Installation_Page
{
    public function getDefaultValues()
    {
        $values = array();

        $values['pagetitle']  = isset($_SESSION['template']['pagetitle']) ? $_SESSION['template']['pagetitle'] : 'Team Clansuite';
        $values['from']       = isset($_SESSION['email']['from']) ? $_SESSION['email']['from'] : 'webmaster@website.com';
        $values['gmtoffset']  = isset($_SESSION['language']['gmtoffset'])  ? $_SESSION['language']['gmtoffset']  : '3600';
        $values['encryption'] = isset($_SESSION['encryption']) ? $_SESSION['encryption'] : 'SHA1';

        return $values;
    }

    public function validateFormValues()
    {
        $error = '';

        # check if input-fields are filled
        if(isset($_POST['config']['template']['pagetitle'])
            and isset($_POST['config']['email']['from'])
            and isset($_POST['config']['language']['gmtoffset']))
        {
            if(!filter_var($_POST['config']['email']['from'], FILTER_VALIDATE_EMAIL))
            {
                $error .= NL. ' Please enter a valid email address.';
            }

            if(preg_match('#!/^[A-Za-z0-9-_\",\'\s]+$/#', $_POST['config']['template']['pagetitle']))
            {
                $error .= NL. ' Please enter a pagetitle containing only alphanumeric characters.';
            }

            if($error != '')
            {
               $this->setErrorMessage($error);
               return false;
            }

            // Values are valid.
            return true;
        }
        else
        {
            $error = $this->language['ERROR_FILL_OUT_ALL_FIELDS'];

            // some input fields are empty
            $this->setErrorMessage($error);

            // Values are not valid.
            return false;
        }
    }

    public function processValues()
    {
        $config_array = array();
        $config_array = $_POST['config'];

        // transform the GMTOFFSET (3600 = GMT+1) into a timezone name, like "Europe/Berlin".
        $config_array['language']['timezone'] = (string) timezone_name_from_abbr('', $_POST['config']['language']['gmtoffset'], 0);

        # write Settings to clansuite.config.php
        if(false === Clansuite_Installation_Helper::write_config_settings($config_array))
        {
            $this->setStep(5);
            $this->setErrorMessage('Config not written <br />');
        }
    }
}
?>
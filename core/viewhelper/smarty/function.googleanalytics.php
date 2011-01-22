<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Google Analytics Plugin
 * Generate XHTML 1.1 valid Google Analytics code
 *
 * Name:     google_analytics<br>
 * Date:     26.Mai 2010.<br>
 *
 * Examples:
 * <pre>
 * {googleanalytics code=UA-xxxxxx-x type=jquery}
 * </pre>
 *
 * @link http://code.google.com/intl/de-DE/apis/analytics/docs/tracking/asyncMigrationExamples.html
 *
 * @param array $params code parameter required
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_googleanalytics($params, $smarty)
{
    # get the google analytics code to insert it later on into the script
    if(empty($params['code']))
    {
        # fallback to config, if nothing was given
        $config = Clansuite_CMS::getInjector('Clansuite_Config');
        $google_id = $config->getConfigValue('googleanalytics_id');

        if(empty($google_id) == false)
        {

        }
        else # no code provided via smarty function nor config
        {
            $smarty->trigger_error("google_analytics: the parameter 'code' is missing. please specifiy your GA urchin id.");
            return;
        }
    }
    else
    {
        $google_id = $params['code'];
    }

    /**
     * Determine the type of script to include
     * a) async = asynchronous script snippet
     * b) jquery = jquery loaded and cached snippet
     */
    if(empty($params['type']))
    {
        $smarty->trigger_error("google_analytics: the parameter 'type' is missing. please specifiy your 'async' or 'jquery'.");
        return;
    }

    if('async' == $params['type'])
    {
        # asynchronous ga script
        $return = '
            <script type="text/javascript">
            // <![CDATA[
            <!-- asynchronous push -->
            var _gaq = _gaq || [];
            _gaq.push([\'_setAccount\', \'' . $google_id . '\']);
            _gaq.push([\'_trackPageview\']);
            <!-- ga object call by inserting it into the page -->
            (function() {
              var ga = document.createElement(\'script\');
                ga.type = \'text/javascript\';
                ga.async = true;
                ga.src = (\'https:\'== document.location.protocol ? \'https://ssl\':\'http://www\') + \'.google-analytics.com/ga.js\';
                var s = document.getElementsByTagName(\'script\')[0];
                s.parentNode.insertBefore(ga, s);
            })();
            // ]]>
            </script>';
    }

    if('jquery' == $params['type'])
    {
        # asynchronous and cached loading via jquery ajax
        $return = '
              <script type="text/javascript">
              // <![CDATA[
              $(document).ready(function(){$.ajax({
                      type: \'GET\',
                      url: \'http://www.google-analytics.com/ga.js\',
                      dataType: \'script\',
                      cache: true,
                      success: function() {
                          var pageTracker = _gat._getTracker(\'' . $google_id . '\');
                          pageTracker._trackPageview();
                        }
                    });
            });
            // ]]>
            </script>';
    }

    return $return;
}

?>
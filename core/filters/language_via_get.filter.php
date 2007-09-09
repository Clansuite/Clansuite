<?php

/**
 * language_via_url Filter Function
 *
 * Purpose: Set Language via URL by appendix $_GET['lang']
 * Example: index.php?lang=langname
 * When request parameter 'lang' is set, the user session value for language will be updated
 *
 * @implements IFilter
 */
class language_via_get implements FilterInterface
{
    private $config     = null;

    function __construct(configuration $config)
    {
       $this->config    = $config;
    }

    public function execute(httprequest $request, httpresponse $response)
    {
        // take the initiative, if language switching is enabled in CONFIG
        // or pass through (do nothing)
        //if($this->config['languageswitch_via_url'] == 1)
       // {
            /**
             * I18N
             *
             * 1) default language is set by config
             *    $cfg->language
             *    - up to this point this language is used for any output, like error-messages
             * NOW:
             * 2) override this by user-selection via URL by $_GET['lang']
             *    and set $_SESSION parameter accordingly
             *
             * notice by vain: to check if language exists is not important,
             *                 because there are 1) english and 2) the default language as fallback
             *
             * @todo: change $request to $request['get']
             */
            if(isset($request['lang']) && !empty($request['lang']) )
            {
            	// Security Handler
                //if( !$input->check( $request['lang'], 'is_abc|is_custom', '_' ) )
                //{
                //    $security->intruder_alert();
                //}
                // Update Session
                //else
                //{
            	   $_SESSION['user']['language']           = $request['lang'];
            	   $_SESSION['user']['language_via_url']   = 1;
            	//}
            }
        //}
    }
}

?>
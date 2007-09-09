<?php
/**
 * Clansuite Filter
 * @package Clansuite
 * @subpackage filters
 */

/**
 * theme_via_url Filter Function
 *
 * Purpose: Sets Theme via URL by appendix $_GET['theme']
 * Example: index.php?theme=themename
 * When request parameter 'theme' is set, the user session value for theme will be updated
 *
 * @implements IFilter
 */
class theme_via_get implements FilterInterface
{
    private $config     = null;
    private $input      = null;
    private $security   = null;
    
    function __construct(configuration $config, input $input, security $security)
    {
       $this->config    = $config;
       $this->input     = $input;
       $this->security  = $security;
    }   
    
    public function execute(httprequest $request, httpresponse $response)
    { 
        // take the initiative, if themeswitching is enabled in CONFIG
        // or pass through (do nothing)
        if($this->config['themeswitch_via_url'] == 1)
        {              
            if(isset($request['theme']) && !empty($request['theme']))
            {   
                #@todo: debug traceing message           
                #echo 'processing themefilter';
            	
            	// Security Handler for $_GET['theme']
            	if( !$this->input->check( $request['theme'], 'is_abc|is_custom', '_' ) )
                {   
                    // @todo: umstellen auf thrown Exception
                    $this->security->intruder_alert();
                }
                
                // If $_GET['theme'] dir exists, set it as session-user-theme
                if(is_dir(ROOT_TPL . '/' . $request['theme'] . '/'))
                {
                    $_SESSION['user']['theme']          = $request['theme'];
                    $_SESSION['user']['theme_via_url']  = 1;
                }
                else
                {
                    $_SESSION['user']['theme_via_url']  = 0;
                }
            }// else => no "?theme=xy" appendix => bypass
        }// else => bypass
    }
}
?>
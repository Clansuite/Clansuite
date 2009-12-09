<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File: outputfilter.tidyrepairhtml.php
* Type: outputfilter
* Name: tidyrepairhtml
* Version: 1.1
* Date: Sept. 20, 2003
*
* Purpose: Uses the tidy extension to repair a mailformed HTML
* template before displaying it
*
* Install: Drop into the plugin directory, call
* $smarty->load_filter('output','tidyrepairhtml');
* from application.
*
* Author: John Coggeshall <john@php.net>
*
* Update: 2005-09-17
* Adjusted to work with tidy for php-4.x and php-5.x
* messju mohr <messju@lammfellpuschen.de>
* -------------------------------------------------------------
*/
function smarty_outputfilter_tidyrepairhtml ($source, $smarty)
{
if(extension_loaded('tidy'))
{
    /*
    $tidyoptions = array("indent-spaces" => 4, 
                         "wrap" => 120, 
                         "indent" =>  auto,
                         "tidy-mark" => true, 
                         "show-body-only" => true, 
                         "force-output" => true,
                         "output-xhtml", true,
                         "clean" => true,
                         "drop-proprietary-attributes" => true,
                         "drop-font-tags" => true,
                         "drop-empty-paras" => true,
                         "hide-comments" => false,
                         "join-classes" => false,
                         "join-styles" => false);   
    */                     
    $opts = array("clean" => true, 
                  "output-xhtml" => true, 
                  "drop-proprietary-attributes" => true, 
                  "show-body-only" => true, 
                  "indent-spaces" => 4, 
                  "wrap" => 130, 
                  "indent" => auto);                    
                         
    if (phpversion() < 5)
    {
        tidy_parse_string($source, $opts, 'UTF8');
        tidy_clean_repair();
        $source = tidy_get_output();
    }
    else
    {
        $tidy = tidy_parse_string($source, $opts, 'UTF8');
        tidy_clean_repair($tidy);
        $source = tidy_get_output($tidy);
    }
}
return $source;
}
?>
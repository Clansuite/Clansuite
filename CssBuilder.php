<?php
/**
 * ---------------------------------------------------------------------------------------------------------
 * CSS-Builder
 * ---------------------------------------------------------------------------------------------------------
 *
 * der CSS-Builder liest die in $builderINI gelisteten Infos und css-dateien ein,
 * komprimiert diese und speichert sie im angegebenen Pfad unter dem in der
 * $builderINI angegebenen 'cssname' ab.
 *
 * @todo:
 * Wenn das Projekt mal steht, den Builder umcoden und im Clansuite-Admin integrieren
 *  - administration der css dateien Ã¼ber editor im admin
 *  - mit dem builder diese nach Ã¤nderung neu kompriemieren
 *
*/

$theme = 'standard';
$patch = 'ie';

$style = "
<style>
    .cmSuccess
    {
        display:block;
        height: auto;
        margin: -0.3em 0 0.5em;
        padding: 6px 10px 5px;
        border: 1px solid #f6abab;
        background: #e5f8ce url('themes/core/images/16x16/success.png') no-repeat 5px 4px;
        color: #62b548;
    }
    .cmSuccess .cmBoxTitle
    {
        font-size: 10pt;
        font-weight:bold;
    }
    .cmSuccess .cmBoxMessage
    {
        font-size: 9pt;
        padding-top: 5px;
        text-align: left;
    }
    .cmSuccess .cmBoxLine
    {
        height: 1px;
        border-bottom: 1px dotted #808080;
    }
</style>
";

echo $style;
echo '<div class="cmSuccess">';
echo '<p class="cmBoxTitle" style="padding-left:50px;">CSS-Builder Information</p>';
echo cssBuilder($theme);
echo '</div>';
echo '<div class="cmSuccess">';
echo '<p class="cmBoxTitle" style="padding-left:50px;">CSS-Builder Information IE-Patch</p>';
echo cssBuilder($theme, $patch);
echo '</div>';
echo '<br />';
echo '<center><a href="index.php">Clansuite Start</a></center>';


// -------------------------------------------------------------------------------------------------
function cssBuilder( $theme='', $patch = '' )
// -------------------------------------------------------------------------------------------------
{
    if( $patch == '' )
    {
        $postfix = '';
    }
    else {
        $postfix = '_' . $patch;
    }

    if( $theme == '' )
    {
        $theme= 'standard';
    }

    $builderINI = 'cssbuilder'.$postfix.'.ini';

    // INI-Files
    $coreINI = 'themes/core/css/csfw/'.$builderINI;
    $themeINI = 'themes/frontend/'.$theme.'/css/'.$builderINI;

    # read INI's
    $coreInfo = read_properties( $coreINI );
    $themeInfo = read_properties( $themeINI );

    /* core */
    $corePath = $coreInfo['path'];
    $coreCssName = $coreInfo['cssname'].$postfix.'.css';
    $coreFiles = explode( ',', $coreInfo['files'] );

    /* theme */
    $themePath = $themeInfo['path'];
    $themeCssName = $themeInfo['cssname'].$postfix.'.css';
    $themeFiles = explode( ',', $themeInfo['files'] );

    $_compact = getCompactHeader($patch);

    $core_compact =  "/**"."\n";
    $core_compact .= " * -----------------------------------------------------------------------------------------------"."\n";
    $core_compact .= " * Framework:   " .$coreInfo['framework']. "\n";
    $core_compact .= " * Description: " .$coreInfo['description']. "\n";
    $core_compact .= " * Version:     " .$coreInfo['version']. "\n";
    $core_compact .= " * Author:      " .$coreInfo['author']. "\n";
    $core_compact .= " * Date:        " .$coreInfo['date']. "\n";
    $core_compact .= " * -------------------------------------------------------------------------------------------------"."\n";
    $core_compact .= "*/"."\n";

    $theme_compact =  "/**"."\n";
    $theme_compact .= " * -----------------------------------------------------------------------------------------------"."\n";
    $theme_compact .= " * Framework:   " .$themeInfo['framework']. "\n";
    $theme_compact .= " * Description: " .$themeInfo['description']. "\n";
    $theme_compact .= " * Version:     " .$themeInfo['version']. "\n";
    $theme_compact .= " * Author:      " .$themeInfo['author']. "\n";
    $theme_compact .= " * Date:        " .$themeInfo['date']. "\n";
    $theme_compact .= " * -------------------------------------------------------------------------------------------------"."\n";
    $theme_compact .= "*/"."\n";

    #-----------------------------------------------------------------------------------------------------------------
    # Build Core CSS
    #-----------------------------------------------------------------------------------------------------------------
    $_comp = $_compact;
    $_comp .= $core_compact;
    foreach( $coreFiles as $filename ) {
        $content = load_stylesheet($corePath.$filename, true);
        $_comp .= "/* [".basename($filename)."] */" . "\n";
        $_comp .= $content."\n";
    }
    save_stylesheet($corePath.$coreCssName,$_comp);
    $html = '<p class="cmBoxMessage" style="padding-left:50px;"><b>Core file:</b> ' .$corePath.$coreCssName .' <b>wurde generiert</b><br>';


    #-----------------------------------------------------------------------------------------------------------------
    # Build Theme CSS
    #-----------------------------------------------------------------------------------------------------------------
    /**
     * create Info + compiled theme stylesheet (1col)
     */
    $_comp = $_compact;
    $_comp .= "/** [Core Import] */\n";
    $_comp .= "@import url('../../../core/css/".$coreCssName."');\n\n";
    $_comp .= $theme_compact;

    foreach( $themeFiles as $filename ) {
        $content = load_stylesheet($themePath.$filename, true);
        $_comp .= "/* [".basename($filename)."] */" . "\n";
        $_comp .= $content."\n";
    }
    save_stylesheet($themePath.$themeCssName,$_comp);
    $html .= '<p class="cmBoxMessage" style="padding-left:50px;"><b>Theme Import file:</b> ' .$themePath.$themeCssName .' <b>wurde generiert und die Core Importiert</b><br>';

    return $html;

}


/**
 * ---------------------------------------------------------
 * Helper methodes
 * ---------------------------------------------------------
*/
function getCompactHeader( $patch = '' )
{
    $compact = '';

    switch($patch) {
        case 'ie':
            $compact =  "/**"."\n";
            $compact .= " * -----------------------------------------------------------------------------------------------"."\n";
            $compact .= " * Clansuite CSS2 Framework (CSFW)"."\n";
            $compact .= " * -------------------------------------------------------------------------------------------------"."\n";
            $compact .= " * Clansuite - just an eSports CMS"."\n";
            $compact .= " * Jens-Andre Koch © 2005 - onwards"."\n";
            $compact .= " * http://www.clansuite.com/"."\n";
            $compact .= " * -----------------------------------------------------------------------------------------------"."\n";
            $compact .= " * @license     GNU/GPL v2 or (at your option) any later version, see \"/doc/LICENSE\"."."\n";
            $compact .= " * @author      Paul Brand <info@isp-tenerife.net>"."\n";
            $compact .= " * @package     CSFW"."\n";
            $compact .= " * @subpackage  Core"."\n";
            $compact .= " * @version     1.0"."\n";
            $compact .= " * -----------------------------------------------------------------------------------------------"."\n";
            $compact .= " * @description  IE-Patch"."\n";
            $compact .= " * -----------------------------------------------------------------------------------------------"."\n";
            $compact .= "*/"."\n";
            $compact .= "\n";
            break;

        default:
            $compact =  "/**"."\n";
            $compact .= " * -----------------------------------------------------------------------------------------------"."\n";
            $compact .= " * Clansuite CSS2 Framework (CSFW)"."\n";
            $compact .= " * -------------------------------------------------------------------------------------------------"."\n";
            $compact .= " * Clansuite - just an eSports CMS"."\n";
            $compact .= " * Jens-Andre Koch © 2005 - onwards"."\n";
            $compact .= " * http://www.clansuite.com/"."\n";
            $compact .= " * -----------------------------------------------------------------------------------------------"."\n";
            $compact .= " * @license     GNU/GPL v2 or (at your option) any later version, see \"/doc/LICENSE\"."."\n";
            $compact .= " * @author      Paul Brand <info@isp-tenerife.net>"."\n";
            $compact .= " * @package     CSFW"."\n";
            $compact .= " * @subpackage  Core"."\n";
            $compact .= " * @version     1.0"."\n";
            $compact .= " * -----------------------------------------------------------------------------------------------"."\n";
            $compact .= "*/"."\n";
            $compact .= "\n";
    }
    return $compact;
}

/**
 * -------------------------------------------------------------------------------------------------
 * read_core_definition
 * -------------------------------------------------------------------------------------------------
 *
 */
function read_properties( $inifile )
{
    $iniArray = parse_ini_file($inifile);
    $iniArray['files'] = str_replace( " ", "", $iniArray['files']);
    $iniArray['files'] = str_replace( "\n", "", $iniArray['files']);
    $iniArray['files'] = str_replace( "\t", "", $iniArray['files']);
    return $iniArray;
}

/**
 * -------------------------------------------------------------------------------------------------
 * save_stylesheet
 * -------------------------------------------------------------------------------------------------
 * save new stylesheet import file
 */
function save_stylesheet($comp_filename, $_compact)
{
    if (!$filehandle = fopen($comp_filename, 'wb'))
    {
        echo 'Could not open file: '.$comp_filename;
        return false;
    }

    if (fwrite($filehandle, $_compact) == false)
    {
        echo 'Could not write to file: '. $comp_filename;
        return false;

    }
    fclose($filehandle);
}

/**
 * -------------------------------------------------------------------------------------------------
 * load_stylesheet
 * -------------------------------------------------------------------------------------------------
 *
 */
function load_stylesheet($file, $optimize = false)
{
  $contents = '';
  if (file_exists($file))
  {
    # Load the local CSS stylesheet.
    $contents = file_get_contents($file);

    # image path anpassen
    $contents = str_replace('../../', '../', $contents);

    # Change to the current stylesheet's directory.
    $cwd = getcwd();
    chdir(dirname($file));

    # Process the stylesheet.
    $contents = load_stylesheet_content($contents, $optimize);

    # Change back directory.
    chdir($cwd);
  }

  return $contents;
}

/**
 * -------------------------------------------------------------------------------------------------
 * load_stylesheet_content
 * -------------------------------------------------------------------------------------------------
 * stylesheet compiler
 */
function load_stylesheet_content($contents, $optimize = false)
{
    # Remove multiple charset declarations for standards compliance (and fixing Safari problems).
    $contents = preg_replace('/^@charset\s+[\'"](\S*)\b[\'"];/i', '', $contents);

    if ($optimize) {
        // Regexp to match comment blocks.
        $comment     = '/\*[^*]*\*+(?:[^/*][^*]*\*+)*/';

        // Regexp to match double quoted strings.
        $double_quot = '"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"';

        // Regexp to match single quoted strings.
        $single_quot = "'[^'\\\\]*(?:\\\\.[^'\\\\]*)*'";

        // Strip all comment blocks, but keep double/single quoted strings.
        $contents = preg_replace( "<($double_quot|$single_quot)|$comment>Ss", "$1", $contents );

        /**
         * Remove certain whitespace.
         * There are different conditions for removing leading and trailing
         * whitespace. To be able to use a single backreference in the replacement
         * string, the outer pattern uses the ?| modifier, which makes all contained
         * subpatterns appear in \1.
         * @see http://php.net/manual/en/regexp.reference.subpatterns.php
         */
        $contents = preg_replace('<
            (?|
            # Strip leading and trailing whitespace.
            \s*([@{};,])\s*
            # Strip only leading whitespace from:
            # - Closing parenthesis: Retain "@media (bar) and foo".
            | \s+([\)])
            # Strip only trailing whitespace from:
            # - Opening parenthesis: Retain "@media (bar) and foo".
            # - Colon: Retain :pseudo-selectors.
            | ([\(:])\s+
            )
            >xS',
            '\1',
            $contents
        );

        # End the file with a new line.
        $contents .= "\n";
    }

    # Replaces @import commands with the actual stylesheet content.
    # This happens recursively but omits external files.
    $contents = preg_replace_callback('/@import\s*(?:url\(\s*)?[\'"]?(?![a-z]+:)([^\'"\()]+)[\'"]?\s*\)?\s*;/', '_load_stylesheet', $contents);

    return $contents;
}
?>
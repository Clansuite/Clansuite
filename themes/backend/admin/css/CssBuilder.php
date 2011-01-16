<?php
/**
 * ---------------------------------------------------------------------------------------------------------
 * CSS-Builder
 * ---------------------------------------------------------------------------------------------------------
*/

echo cssBuilder();

// -------------------------------------------------------------------------------------------------
function cssBuilder()
// -------------------------------------------------------------------------------------------------
{
    $builderINI = 'cssbuilder.ini';

    $inipath = '';
    $themeINI = $inipath.$builderINI;

    # read INI's
    $themeInfo = read_properties( $themeINI );

    //var_dump($themeInfo); exit;

    /* theme */
    $themeCssFramework = $themeInfo['framework'];
    $themeCssDescription = $themeInfo['description'];
    $themeCssVersion = $themeInfo['version'];
    $themeCssAuthor = $themeInfo['author'];
    $themeCssDate = $themeInfo['date'];
    $themePath = $themeInfo['path'];
    $themeCssName = $themeInfo['cssname'];
    $themeImportFile1 = $themeInfo['importfile1'];
    $themeImportFile2 = $themeInfo['importfile2'];
    $themeImportFile3 = $themeInfo['importfile3'];
    $themeFiles = array($themeInfo['files']);
    $themeFiles = explode( ',', $themeInfo['files'] );

    $_compact =  "/**"."\n";
    $_compact .= " * -----------------------------------------------------------------------------------------------"."\n";
    $_compact .= " * Clansuite CSS2 Framework (CSFW)"."\n";
    $_compact .= " * -------------------------------------------------------------------------------------------------"."\n";
    $_compact .= " * Clansuite - just an eSports CMS"."\n";
    $_compact .= " * Jens-André Koch © 2005 - onwards"."\n";
    $_compact .= " * http://www.clansuite.com/"."\n";
    $_compact .= " * -----------------------------------------------------------------------------------------------"."\n";
    $_compact .= " * @license    GNU/GPL v2 or (at your option) any later version, see \"/doc/LICENSE\"."."\n";
    $_compact .= " * @author     Jens-André Koch <vain@clansuite.com>"."\n";
    $_compact .= " * @author     Paul Brand <info@isp-tenerife.net>"."\n";
    $_compact .= " * @package    CSFW"."\n";
    $_compact .= " * @subpackage Core"."\n";
    $_compact .= " * @version    1.0"."\n";
    $_compact .= " * -----------------------------------------------------------------------------------------------"."\n";
    $_compact .= "*/"."\n";
    $_compact .= "\n";

    $theme_compact =  "/**"."\n";
    $theme_compact .= " * -----------------------------------------------------------------------------------------------"."\n";
    $theme_compact .= " * Framework:    " .$themeCssFramework. "\n";
    $theme_compact .= " * Description:  " .$themeCssDescription. "\n";
    $theme_compact .= " * Version:      " .$themeCssVersion. "\n";
    $theme_compact .= " * Author:       " .$themeCssAuthor. "\n";
    $theme_compact .= " * Date:         " .$themeCssDate. "\n";
    $theme_compact .= " * -------------------------------------------------------------------------------------------------"."\n";
    $theme_compact .= "*/"."\n";

    #-----------------------------------------------------------------------------------------------------------------
    # Build Theme CSS
    #-----------------------------------------------------------------------------------------------------------------
    /**
     * create Info + compiled theme stylesheet
     */
    $_comp = $_compact;
    if( $themeImportFile1 !='' )
    {
        $_comp .= "/** [Imports] */\n";
        $_comp .= "@import url('".$themeImportFile1."');\n";
        if( $themeImportFile2 !='' )
        {
            $_comp .= "@import url('".$themeImportFile2."');\n";
        }
        if( $themeImportFile3 !='' )
        {
            $_comp .= "@import url('".$themeImportFile3."');\n";
        }
        $_comp .= "\n";
    }
    $_comp .= $theme_compact;

    foreach( $themeFiles as $filename ) {
        $content = load_stylesheet($themePath.$filename, true);
        $_comp .= "/* [".$filename."] */" . "\n";
        $_comp .= $content."\n";
    }
    save_stylesheet($themePath.$themeCssName,$_comp);
    $html .= '<p class="cmBoxMessage" style="padding-left:50px;"><b>Theme Import file:</b> ' .$themePath.$themeCssName .' <b>wurde generiert</b><br>';

    return $html;

}


/**
 * ---------------------------------------------------------
 * Helper methodes
 * ---------------------------------------------------------
*/

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
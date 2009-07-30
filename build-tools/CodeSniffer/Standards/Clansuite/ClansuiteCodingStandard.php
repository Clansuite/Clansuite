<?php
/**
 * Clansuite Coding Standard
 *
 * @category   Clansuite
 * @package    BuildTools
 * @subpackage PHP_CodeSniffer_CodingStandard
 * @author     Jens-André Koch <jakoch@web.de>
 * @license    GPLv2 and any later version
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PHP_CodeSniffer
 */

if (class_exists('PHP_CodeSniffer_Standards_CodingStandard', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_CodingStandard not found');
}

/**
 * Clansuite Coding Standard
 *
 * 1. Standard Class
 * @link http://pear.php.net/manual/de/package.php.php-codesniffer.coding-standard-class.php
 *
 * 2. Available Sniffs
 * @link http://pear.php.net/reference/PHP_CodeSniffer-0.6.0/li_PHP_CodeSniffer.html
 *
 * @category   Clansuite
 * @package    BuildTools
 * @subpackage PHP_CodeSniffer_CodingStandard
 * @author     Jens-André Koch <jakoch@web.de>
 * @license    GPLv2 and any later version
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PHP_CodeSniffer
 */
class PHP_CodeSniffer_Standards_Clansuite_ClansuiteCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
{
    /**
     * Return a list of external sniffs to include with this standard.
     *
     * @return array
     */
    public function getIncludedSniffs()
    {
        return array(
                     # Files
                     'Generic/Sniffs/Files/LineEndingsSniff.php',
                     'PEAR/Sniffs/Files/IncludingFileSniff.php',

                     # Formatting
                     #'Generic/Sniffs/Formatting/SpaceAfterCastSniff.php',

                     # Functions
                     #'Generic/Sniffs/Functions/OpeningFunctionBraceBsdAllmanSniff.php',
                     #'PEAR/Sniffs/Functions/ValidDefaultValueSniff.php',

                     # PHP
                     #'Generic/Sniffs/PHP/NoSilencedErrorsSniff.php',
                     #'Generic/Sniffs/PHP/UpperCaseConstantSniff.php',
                     #'Squiz/Sniffs/PHP/GlobalKeywordSniff.php',
                     #'Squiz/Sniffs/PHP/EvalSniff.php',

                     # WhiteSpace
                     #'Squiz/Sniffs/WhiteSpace/',
                     #'Generic/Sniffs/WhiteSpace/DisallowTabIndentSniff.php',
                     #'Generic/Sniffs/WhiteSpace/ScopeIndentSniff.php',
                     #'PEAR/Sniffs/WhiteSpace/ScopeClosingBraceSniff.php',

                     # ControlStructures
                     #'Generic/Sniffs/ControlStructures/InlineControlStructureSniff.php',
                     #'Squiz/Sniffs/ControlStructures/InlineIfDeclarationSniff.php',
                     #'Squiz/Sniffs/ControlStructures/ControlSignatureSniff.php',
                     #'Squiz/Sniffs/ControlStructures/ForEachLoopDeclarationSniff.php',
                     #'Squiz/Sniffs/ControlStructures/SwitchDeclarationSniff.php',

                     # VersionControl -> SVN
                     #'Generic/Sniffs/VersionControl/SubversionPropertiesSniff.php',

                     # CSS
                     #'Squiz/Sniffs/CSS',

                     # Strings
                     #'Squiz/Sniffs/Strings/EchoedStringsSniff.php',

                     # Array
                     #'Squiz/Sniffs/Arrays/ArrayDeclarationSniff.php',
                   );

    }

    /**
     * Return a list of external sniffs to exclude from this standard.
     *
     * @return array
     */
    public function getExcludedSniffs()
    {
        return array(
                    );

    }
}
?>
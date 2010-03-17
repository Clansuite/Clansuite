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
                     # Ensures there is a single space after cast tokens.
                     'Generic/Sniffs/Formatting/SpaceAfterCastSniff.php',
                     'Squiz/Sniffs/Strings/DoubleQuoteUsageSniff.php',

              # Functions - Braces
                     # Checks that the opening brace of a function is on the line after the function declaration.
                     'Generic/Sniffs/Functions/OpeningFunctionBraceBsdAllmanSniff.php',
                     //'PEAR/Sniffs/Functions/ValidDefaultValueSniff.php',

                     'Squiz/Sniffs/PHP/LowercasePHPFunctionsSniff.php',

              # Classes
                     'Squiz/Sniffs/Classes/LowercaseClassKeywordsSniff.php',
                     'Squiz/Sniffs/Classes/SelfMemberReferenceSniff.php',

              # PHP
                     'Generic/Sniffs/PHP/NoSilencedErrorsSniff.php',

                     # Checks that all uses of true, false and null are lowerrcase.
                     'Generic/Sniffs/PHP/LowerCaseConstantSniff.php'
                     # Stops the usage of the "global" keyword.
                     'Squiz/Sniffs/PHP/GlobalKeywordSniff.php',

                     # The use of eval() is discouraged.
                     'Squiz/Sniffs/PHP/EvalSniff.php',

                     # Makes sure that shorthand PHP open tags are not used.
                     'Generic/Sniffs/PHP/DisallowShortOpenTagSniff',

              # WhiteSpace
                     //'Squiz/Sniffs/WhiteSpace/',
                     'Generic/Sniffs/WhiteSpace/DisallowTabIndentSniff.php',
                     //'Generic/Sniffs/WhiteSpace/ScopeIndentSniff.php',
                     //'PEAR/Sniffs/WhiteSpace/ScopeClosingBraceSniff.php',

              # ControlStructures
                     //'Generic/Sniffs/ControlStructures/InlineControlStructureSniff.php',
                     //'Squiz/Sniffs/ControlStructures/InlineIfDeclarationSniff.php',
                     //'Squiz/Sniffs/ControlStructures/ControlSignatureSniff.php',
                     //'Squiz/Sniffs/ControlStructures/ForEachLoopDeclarationSniff.php',
                     //'Squiz/Sniffs/ControlStructures/SwitchDeclarationSniff.php',

              # VersionControl -> SVN
                     //'Generic/Sniffs/VersionControl/SubversionPropertiesSniff.php',

              # CSS
                     //'Squiz/Sniffs/CSS',

              # Strings
                     //'Squiz/Sniffs/Strings/EchoedStringsSniff.php',

              # Array
                     //'Squiz/Sniffs/Arrays/ArrayDeclarationSniff.php',

              # Naming Conventions
                    # Ensures class and interface names start with a capital letter and use _ separators.
                    'PEAR/Sniffs/NamingConventions/ValidClassNameSniff',
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
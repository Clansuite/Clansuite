<?php
/**
 * Clansuite Coding Standard
 *
 * @category   Clansuite
 * @package    BuildTools
 * @subpackage PHP_CodeSniffer_CodingStandard
 * @author     Jens-Andr� Koch <jakoch@web.de>
 * @license    GPLv2 and any later version
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PHP_CodeSniffer
 */

if (class_exists('PHP_CodeSniffer_Standards_CodingStandard', true) === false)
{
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_CodingStandard not found');
}

/**
 * Clansuite Coding Standard
 *
 * 1. Standard Class
 * @link http://pear.php.net/manual/de/package.php.php-codesniffer.coding-standard-class.php
 *
 * 2. Available Sniffs
 * @link http://pear.php.net/package/PHP_CodeSniffer/docs/latest/
 *
 * @category   Clansuite
 * @package    BuildTools
 * @subpackage PHP_CodeSniffer_CodingStandard
 * @author     Jens-Andr� Koch <jakoch@web.de>
 * @license    GPLv2 and any later version
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PHP_CodeSniffer
 */
class PHP_CodeSniffer_Standards_Clansuite_ClansuiteCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
{
    public static function setIncludePath()
    {
        $paths = array(
                        dirname(dirname(__FILE__))
                      );

        set_include_path( implode( $paths, PATH_SEPARATOR ) . PATH_SEPARATOR . get_include_path() ); # attach original include paths
        unset($paths);    
    }
    
    /**
     * Return a list of external sniffs to include with this standard.
     *
     * @return array
     */
    public function getIncludedSniffs()
    {
        self::setIncludePath();
        
        return array(
              # Files
                     #'Generic/Sniffs/Files/LineEndingsSniff.php',
                     'PEAR/Sniffs/Files/IncludingFileSniff.php',

              # Formatting
                     # Ensures there is a single space after cast tokens.
                     'Generic/Sniffs/Formatting/SpaceAfterCastSniff.php',
                     'Squiz/Sniffs/Strings/DoubleQuoteUsageSniff.php',

              # Functions - Braces
                     # Checks that the opening brace of a function is on the line after the function declaration.
                     'Generic/Sniffs/Functions/OpeningFunctionBraceBsdAllmanSniff.php',
                     //'PEAR/Sniffs/Functions/ValidDefaultValueSniff.php',

                     # lowercased function names
                     'Squiz/Sniffs/PHP/LowercasePHPFunctionsSniff.php',

                     # One Line - one statement
                     'Generic/Sniffs/Formatting/DisallowMultipleStatementsSniff.php',

                     # CA/PMD: Detects unnecessary overriden methods that simply call their parent.
                     'Generic/Sniffs/CodeAnalysis/UselessOverridingMethodSniff.php',

                     # CA/PMD: Detect double usage of incrementation variables (in inner and outer loops)
                     'Generic/Sniffs/CodeAnalysis/JumbledIncrementerSniff.php',

                     # discourage several functions in clansuite and ensure consistent usage of functions
                     dirname(__FILE__) . '/Clansuite/Sniffs/ForbiddenFunctionsSniff.php',

              # Classes
                     'Squiz/Sniffs/Classes/LowercaseClassKeywordsSniff.php',
                     'Squiz/Sniffs/Classes/SelfMemberReferenceSniff.php',

              # Constructor

                     # enforce PHP 5 constructor syntax "function __construct()"
                     'Generic/Sniffs/NamingConventions/ConstructorNameSniff.php',

              # PHP
                     # Usage of @ is not allowed.
                     'Generic/Sniffs/PHP/NoSilencedErrorsSniff.php',

                     # Stops the usage of the "global" keyword.
                     'Squiz/Sniffs/PHP/GlobalKeywordSniff.php',

                     # The use of eval() is discouraged.
                     'Squiz/Sniffs/PHP/EvalSniff.php',

                     # Makes sure that shorthand PHP open tags are not used.
                     'Generic/Sniffs/PHP/DisallowShortOpenTagSniff.php',

                     'Generic/Sniffs/CodeAnalysis/EmptyStatementSniff.php',

                     # Detects for-loops that use a function call in the test expression.
                     'Generic/Sniffs/CodeAnalysis/ForLoopWithTestFunctionCallSniff.php',

              # WhiteSpace
                     # No Tabs. Just Spaces.
                     'Generic/Sniffs/WhiteSpace/DisallowTabIndentSniff.php',
                     # Scope Idention - default value: 4
                     'Generic/Sniffs/WhiteSpace/ScopeIndentSniff.php',
                     # Alignment of Scope Closing Braces
                     'PEAR/Sniffs/WhiteSpace/ScopeClosingBraceSniff.php',
                     # No Space before semicolon
                     'Squiz/Sniffs/WhiteSpace/SemicolonSpacingSniff.php',
                     # Spaces before+after Scope Keywords
                     'Squiz/Sniffs/WhiteSpace/ScopeKeywordSpacingSniff.php',

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
                     # Makes sure that any strings that are "echoed" are not enclosed in brackets like a function call.
                     'Squiz/Sniffs/Strings/EchoedStringsSniff.php',

              # Array
                     //'Squiz/Sniffs/Arrays/ArrayDeclarationSniff.php',

              # Naming Conventions
                    # Ensures class and interface names start with a capital letter and use _ separators.
                    'PEAR/Sniffs/NamingConventions/ValidClassNameSniff.php',
                    # Ensures all control structure keywords are lowercase.
                    'Squiz/Sniffs/ControlStructures/LowercaseDeclarationSniff.php',
                    # Checks that all uses of true, false and null are lowerrcase.
                    'Generic/Sniffs/PHP/LowerCaseConstantSniff.php',
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
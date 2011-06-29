<?php
/**
 * Clansuite_Sniffs_Commenting_UnnecessaryFinalModifierSniff.
 *
 * This sniff detects unnecessary final modifiers inside of final classes.
 *
 * This rule is based on the PMD rule catalog. The Unnecessary Final Modifier
 * sniff detects the use of the final modifier inside of a final class which
 * is unnecessary.
 *
 * <code>
 * final class Foo
 * {
 *     public final function bar()
 *     {
 *     }
 * }
 * </code>
 *
 * @author    Nicolas Connault
 * @copyright 2009 Nicolas Connault
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author    Jens-Andre Koch
 * @copyright 2005-onwards
 *
 * @category   PHP
 * @package    PHP_CodeSniffer
 * @subpackage Clansuite_Sniffs
 */
class Clansuite_Sniffs_Codeanalysis_UnnecessaryFinalModifierSniff implements Php_CodeSniffer_Sniff
{

    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return array(integer)
     */
    public function register()
    {
        return array(T_CLASS);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsfile The file being scanned.
     * @param int                  $stackptr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsfile, $stackptr)
    {
        $tokens = $phpcsfile->gettokens();
        $token = $tokens[$stackptr];

        // Skip for-statements without body.
        if(isset($token['scope_opener']) === false)
        {
            return;
        }

        // Fetch previous token.
        $prev = $phpcsfile->findprevious(PHP_CodeSniffer_tokens::$emptyTokens, ($stackptr - 1), null, true);

        // Skip for non final class.
        if($prev === false || $tokens[$prev]['code'] !== T_FINAL)
        {
            return;
        }

        $next = ++$token['scope_opener'];
        $end = --$token['scope_closer'];

        for(; $next <= $end; ++$next)
        {
            if($tokens[$next]['code'] === T_FINAL)
            {
                $error = 'Unnecessary FINAL modifier in FINAL class';
                $phpcsfile->addwarning($error, $next);
            }
        }
    }
}

?>
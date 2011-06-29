<?php
/**
 * Clansuite_Sniffs_Whitespace_ControlStructureBlankLineSniff.
 *
 * This sniff enforces a blank line before control structures and commented control structures.
 
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
class Clansuite_Sniffs_Whitespace_ControlStructureBlankLineSniff implements Php_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_IF, T_FOR, T_FOREACH, T_WHILE, T_SWITCH, T_TRY, T_CATCH);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsfile All the tokens found in the document.
     * @param int                  $stackptr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsfile, $stackptr)
    {
        $tokens = $phpcsfile->gettokens();
        $previoustoken = $stackptr - 1;

        // Move back until we find the previous non-whitespace, non-comment token
        do
        {
            $previoustoken = $phpcsfile->findprevious(array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT),
                                                      ($previoustoken - 1), null, true);

        }
        while ($tokens[$previoustoken]['line'] == $tokens[$stackptr]['line']);

        $previous_non_ws_token = $tokens[$previoustoken];

        // If this token is immediately on the line before this control structure, print a warning
        if ($previous_non_ws_token['line'] == ($tokens[$stackptr]['line'] - 1))
        {
            // Exception: do {EOL...} while (...);
            if ($tokens[$stackptr]['code'] == T_WHILE && $tokens[($stackptr - 1)]['code'] == T_CLOSE_CURLY_BRACKET)
            {
                // Ignore do...while (see above)
            }
            else
            {
                $phpcsfile->addWarning('You should add a blank line before control structures', $stackptr);
            }
        }
    }
}
?>
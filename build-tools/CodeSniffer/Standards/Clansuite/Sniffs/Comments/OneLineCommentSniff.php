<?php
/**
 * Clansuite_Sniffs_Commenting_OneLineCommentSniff. 
 *
 * This sniff prohibits the usage of "//" on one line comments.
 * Usage of "#" is enforced.
 * 
 * @author    Jens-Andre Koch
 * @copyright 2005-onwards
 * @license   GPLv2+
 *
 * @category   PHP
 * @package    PHP_CodeSniffer
 * @subpackage Clansuite_Sniffs
 */
class Clansuite_Sniffs_Commenting_OneLineCommentSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_COMMENT
        );
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile File being scanned
     * @param int $stackPtr Position of the current token in the stack
     *        passed in $tokens.
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $content = $tokens[$stackPtr]['content'];

        # @todo
        if (preg_match('/^\s*(?:\/\/[^ ]|#)/', $content)) {
            $error = 'Single-line comments must begin with "// " (e.g. // My comment)';
            $phpcsFile->addError($error, $stackPtr);
        }
    }
}

?>
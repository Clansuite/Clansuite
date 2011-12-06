<?php
/**
 * Clansuite_Sniffs_Methods_ValidDefaultValueSniff.
 *
 * The sniff ensures that function parameters with default value 
 * are positioned at the end of the function signature.
 *
 * @author    Jens-Andre Koch
 * @copyright 2005-onwards
 * @license   GPLv2+
 *
 * @category   PHP
 * @package    PHP_CodeSniffer
 * @subpackage Clansuite_Sniffs
 */
class Clansuite_Sniffs_Methods_ValidDefaultValueSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_FUNCTION);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $argStart = $tokens[$stackPtr]['parenthesis_opener'];
        $argEnd   = $tokens[$stackPtr]['parenthesis_closer'];

        // Flag for when we have found a default in our arg list.
        // If there is a value without a default after this, it is an error.
        $defaultFound = false;

        $nextArg = $argStart;
        while (($nextArg = $phpcsFile->findNext(T_VARIABLE, $nextArg + 1, $argEnd)) !== false)
        {
            $argHasDefault = self::_argHasDefault($phpcsFile, $nextArg);

            if (($argHasDefault === false) && ($defaultFound === true))
            {
                $error  = 'Arguments with default values should be at the end of the argument list.';
                $phpcsFile->addError($error, $nextArg);
                return;
            }

            if ($argHasDefault === true)
            {
                $defaultFound = true;
            }
        }
    }

    /**
     * Returns true if the passed argument has a default value.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int $argPtr The position of the argument in the stack.
     *
     * @return bool
     */
    private static function _argHasDefault(PHP_CodeSniffer_File $phpcsFile, $argPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $nextToken = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, $argPtr + 1, null, true);

        if ($tokens[$nextToken]['code'] !== T_EQUAL)
        {
            return false;
        }

        return true;

    }
}
?>
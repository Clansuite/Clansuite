<?php
/**
 * Clansuite_Sniffs_PHP_DeprecatedNewReferenceSniff
 *
 * Discourages the use of 
 * 1) STATIC magic methods
 * 2) protected magic methods
 * 3) private magic methods
 * 
 * This is a sniff for PHP 5.3 compatibility.
 *
 * @author    Wim Godden <wim.godden@cu.be>
 * @copyright 2010 Cu.be Solutions bvba
 *
 * @author    Jens-Andre Koch
 * @copyright 2011-onwards
 * @license   GPLv2+
 *
 * @category   PHP
 * @package    PHP_CodeSniffer
 * @subpackage Clansuite_Sniffs
 */
class Clansuite_Sniffs_PHP_DeprecatedNewReferenceSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * If true, an error will be thrown; otherwise a warning.
     *
     * @var bool
     */
    protected $error = true;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_NEW);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int $stackPtr The position of current token in the stack passed in $tokens.
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        
        if ($tokens[$stackPtr - 1]['type'] == 'T_BITWISE_AND' or $tokens[$stackPtr - 2]['type'] == 'T_BITWISE_AND')
        {
            $error = 'Assigning the return value of new by reference is deprecated in PHP 5.3';
            $phpcsFile->addError($error, $stackPtr);
        }
    }
}
?>
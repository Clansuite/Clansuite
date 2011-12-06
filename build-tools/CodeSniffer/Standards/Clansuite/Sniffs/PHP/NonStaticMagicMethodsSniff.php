<?php
/**
 * Clansuite_Sniffs_PHP_NonStaticMagicMethodsSniff
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
class Clansuite_Sniffs_PHP_NonStaticMagicMethodsSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * The following magic methods must NOT be static, but public
     *
     * @var array
     */
    protected $magicMethods = array(
        '__get',
        '__set',
        '__isset',
        '__unset',
        '__call'
    );

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $stackPtr
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $functionToken = $phpcsFile->findNext(T_FUNCTION, $stackPtr);
        if ($functionToken === false)
        {
            return;
        }

        $nameToken = $phpcsFile->findNext(T_STRING, $functionToken);
        if (in_array($tokens[$nameToken]['content'], $this->magicMethods) === false)
        {
            return;
        }

        $scopeToken = $phpcsFile->findPrevious(array(T_PUBLIC, T_PROTECTED, T_PRIVATE), $nameToken, $stackPtr);
        if ($scopeToken === false)
        {
            return;
        }

        if ($tokens[$scopeToken]['type'] != 'T_PUBLIC')
        {
            $error = "As of PHP 5.3 Magic methods must be public!";
            $phpcsFile->addError($error, $stackPtr);
        }

        $staticToken = $phpcsFile->findPrevious(T_STATIC, $scopeToken, $scopeToken - 2);
        if ($staticToken === false)
        {
            return;
        }
        else
        {
            $error = "As of PHP 5.3 Magic methods must be non static!";
            $phpcsFile->addError($error, $stackPtr);
        }
    }

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_CLASS, T_INTERFACE);
    }
}
?>
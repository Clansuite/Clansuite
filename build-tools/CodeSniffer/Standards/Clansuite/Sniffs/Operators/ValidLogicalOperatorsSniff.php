<?php
/**
 * Clansuite_Sniffs_Operators_ValidLogicalOperatorsSniff
 *
 * Discourages the use of '&&' '||' '^' as representation for logical operators.
 * Ensures that 'and' 'or' 'xor' are used as logical operators.
 *
 * @author    Jens-Andre Koch
 * @copyright 2005-onwards
 * @license   GPLv2+
 *
 * @category   PHP
 * @package    PHP_CodeSniffer
 * @subpackage Clansuite_Sniffs
 */
class Clansuite_Sniffs_Operators_ValidLogicalOperatorsSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_LOGICAL_AND,
            T_LOGICAL_OR,
            T_LOGICAL_XOR,
        );
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The current file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                         stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {

        $tokens = $phpcsFile->getTokens();

        $replacements = array(
            '&&' => 'and',
            '||' => 'or',
            '^'  => 'xor',
        );

        $operator = strtolower($tokens[$stackPtr]['content']);
        if(isset($replacements[$operator]) === false)
        {
            return;
        }

        $replacement = $replacements[$operator];
        $error = "Logical operator \"$operator\" is prohibited; use \"$replacement\" instead.";
        $phpcsFile->addError($error, $stackPtr);
    }
}
?>
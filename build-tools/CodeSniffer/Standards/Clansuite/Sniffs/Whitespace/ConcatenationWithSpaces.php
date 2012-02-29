<?php
/**
 * The Sniff enforces spacings around the concatenation operator.

 * Forbidden - concat without spaces:
 * $string = 'Hello'.'World';
 *
 * Accepted - concat with spaces:
 * $string = 'Hello' . 'World';
 *
 * Note: see example above, still worse, because concat is senseless here.
 * @todo sniff irrelevant concat, which are concats without var between the strings to concat
 */
class Clansuite_Sniffs_Whitespace_ConcatenationWithSpacesSniff implements PHP_CodeSniffer_Sniff
{
    public function register()
    {
        return array(T_STRING_CONCAT);
    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE
        or  $tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE)
        {

            $phpcsFile->addError('Concatenation operator must be surrounded by whitespace', $stackPtr);
        }
    }
}
?>
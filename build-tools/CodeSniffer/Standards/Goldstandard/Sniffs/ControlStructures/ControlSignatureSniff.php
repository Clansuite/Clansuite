<?php
if (class_exists('PHP_CodeSniffer_Standards_AbstractPatternSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_AbstractPatternSniff not found');
}
/**
 * Verifies that control structures conform to their coding standards.
 *
 * Enforcing brackets on new line (Allman-Style).
 *
 * @author    Jens-Andre Koch
 * @copyright 2005-onwards
 * @license   GPLv2+
 *
 * @category   PHP
 * @package    PHP_CodeSniffer
 */
class Goldstandard_Sniffs_ControlStructures_ControlSignatureSniff extends PHP_CodeSniffer_Standards_AbstractPatternSniff
{

    public function __construct()
    {
        parent::__construct(true);
    }

    /**
     * Returns the patterns that this test wishes to verify.
     *
     * @return array(string)
     */
    protected function getPattern()
    {
        return self::getPatternsInKRStyle();
    }

    /**
     * Returns the patterns that this test wishes to verify.
     * Enforcing brackets in Kernighan and Ritchie (C,C++,C#) Style.
     *
     * @return array(string)
     */
    private function getPatternsInKRStyle()
    {
        return array(
            'do {EOL...} while (...);EOL',
            'while (...) {EOL',
            'for (...) {EOL',
            'if (...) {EOL',
            'foreach (...) {EOL',
            '}EOL else if (...) {EOL',
            '}EOL elseif (...) {EOL',
            '}EOL else {EOL',
            'do {EOL',
         );
    }

    /**
     * Returns the patterns that this test wishes to verify.
     * Enforcing brackets on new line (Allman-Style).
     *
     * @return array(string)
     */
    private function getPatternsInAllmanStyle()
    {
        return array(
            'tryEOL...{EOL...}EOL...catch (...)EOL...{EOL',
            'doEOL...{EOL...}EOL...while (...);EOL',
            'for (...)EOL...{EOL',
            'if.(...)EOL...{EOL',
            'foreach (...)EOL...{EOL',
            '}EOL...else if (...)EOL...{EOL',
            '}EOL...elseif (...)EOL...{EOL',
            '}EOL...elseEOL{EOL...EOL',
            'switch (...)EOL...{EOL',
        );
    }
}

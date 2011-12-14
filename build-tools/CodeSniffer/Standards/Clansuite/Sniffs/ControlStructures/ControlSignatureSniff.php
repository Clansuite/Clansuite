<?php
/**
 * Verifies that control structures conform to their coding standards.
 *
 * This is an reversed and extended version of the Squiz ControlSignatureSniff.
 * Enforcing Brackets on new line => Allman-Style.
 *
 * @author    Jens-Andre Koch
 * @copyright 2005-onwards
 * @license   GPLv2+
 *
 * @category   PHP
 * @package    PHP_CodeSniffer
 */
class Clansuite_Sniffs_ControlStructures_ControlSignatureSniff extends PHP_CodeSniffer_Standards_AbstractPatternSniff
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
    protected function getPatterns()
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
?>
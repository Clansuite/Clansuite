<?php
/**
 * Clansuite_Sniffs_Commenting_TodoSniff.
 *
 * This sniff warns about @todo annotations without links to our bugtracker.
 *
 * @author    Jens-Andre Koch
 * @copyright 2005-onwards
 * @license   GPLv2+
 *
 * @category   PHP
 * @package    PHP_CodeSniffer
 * @subpackage Clansuite_Sniffs
 */
class Clansuite_Sniffs_Commenting_TodoWithBugtrackerLinkSniff implements PHP_CodeSniffer_Sniff
{

  /**
   * A list of tokenizers this sniff supports.
   *
   * @var array
   */
  public $supportedTokenizers = array(
    'PHP',
    'JS',
  );

  /**
   * Returns an array of tokens this test wants to listen for.
   *
   * @return array
   */
  public function register()
  {
    return PHP_CodeSniffer_Tokens::$commentTokens;
  }


  /**
   * Processes this sniff, when one of its tokens is encountered.
   *
   * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
   * @param int                  $stackPtr  The position of the current token
   *                                        in the stack passed in $tokens.
   */
  public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    $tokens = $phpcsFile->getTokens();

    $content = $tokens[$stackPtr]['content'];
    $matches = array();

    if (preg_match('|[^a-z]+todo[^a-z]+(.*)|i', $content, $matches) !== 0)
    {
        // Clear whitespace and some common characters not required at
        // the end of a to-do message to make the warning more informative.
        $todoMessage = trim($matches[1]);

        # ensure that every "todo message" contains a link to the corresponding ticket in the bugtracker
        if(false === strpos($todoMessage, 'http://trac.clansuite.com/ticket/') or
           false === strpos($todoMessage, 'http://clansuite.com/trac/ticket/'))
        {
            $type  = 'TodoTaskWithoutBugtrackerLinkFound';
            $error = 'A ToDo-Item without a link to the bugtracker was found "%s"';
        }

        $phpcsFile->addWarning($error, $stackPtr, $type, array($todoMessage));
    }
  }
}

?>
<?php
/**
 * Clansuite_Sniffs_PHP_OpeningClosingTagSniff.
 *
 * This sniff enforces the usage of <?php ... ?> as opening and closing tags for php.
 *
 * This sniff forbids the usage of
 * a) ASP tags                  <%  ... %>
 * b) ASP tags with echo        <%= ... %>
 * c) Short tags                <?  ... ?>
 * d) Short tags with echo      <?= ... ?>
 * e) Long tags                 <script language="php"> ... </script>
 *
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
class Clansuite_Sniffs_PHP_OpeningClosingTagSniff implements Php_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_TAG, T_OPEN_TAG_WITH_ECHO);
    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsfile The file being scanned.
     * @param int                  $stackptr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsfile, $stackptr)
    {

        /**
         * Check if Short tags are allowed <? <?= ?>
         *
         * If short open tags are off, then any short open tags 
         * will be converted to inline_html tags and are simply ignored.
         * If short open tags are on, we want to forbid them.
         */
        # Ini_get returns a string "0" if short open tags is off.
        if (ini_get('short_open_tag') !== '0')
        {

            $tokens  = $phpcsfile->gettokens();
            $opentag = $tokens[$stackptr];

            if ($opentag['content'] === '<?' or $opentag['content'] === '<?=')
            {
                $error = 'Short PHP opening tag used. Found "'.$opentag['content'].'" Expected "<?php".';
                $phpcsfile->adderror($error, $stackptr);
            }

            if ($opentag['code'] === T_OPEN_TAG_WITH_ECHO)
            {
                $nextvar = $tokens[$phpcsfile->findnext(PHP_CodeSniffer_tokens::$emptyTokens, ($stackptr + 1), null, true)];
                $error   = 'Short PHP opening tag used with echo. Found "';
                $error  .= $opentag['content'].' '.$nextvar['content'].' ..." but expected "<?php echo '.
                           $nextvar['content'].' ...".';
                $phpcsfile->adderror($error, $stackptr);
            }
        }

        if (ini_get('asp_tags') !== '0')
        {         
            if ($opentag['content'] === '<%' or $opentag['content'] === '<%=')
            {
                $error = 'ASP PHP opening tag used. Found "'.$opentag['content'].'" Expected "<?php".';
                $phpcsfile->adderror($error, $stackptr);
            }
         
        }
    }
}

?>
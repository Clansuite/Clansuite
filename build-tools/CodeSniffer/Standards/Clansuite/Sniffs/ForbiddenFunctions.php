<?php
/**
 * Clansuite_Sniffs_ForbiddenFunctions
 *
 * BE AWARE THAT THIS IS A BADASS SNIFF.
 *
 * 1) Discourages the use of alias functions that are kept in PHP for compatibility with older versions.
 * 2) Discourages the use of Clansuite Debugging functions (our own debugging helper methods)
 * 3) Discourages the use of PHP debugging functions
 * 4) Discourages the use of normale string functions, thereby enforces the usage of mbstring functions
 *    (Discourages mb overloading - @see http://php.net/manual/de/mbstring.overload.php)
 * 5) Discourages the use of ereg-functions in general = no ereg*() and no mb_ereg_*()
 *
 * @author    Jens-Andre Koch
 * @copyright 2005-onwards
 * @license   GPLv2+
 *
 * @category   PHP
 * @package    PHP_CodeSniffer
 * @subpackage Clansuite_Sniffs
 */
class Clansuite_Sniffs_ForbiddenFunctions extends Generic_Sniffs_PHP_ForbiddenFunctionsSniff
{
    /**
     * A list of forbidden functions with their alternatives.
     *
     * @var array(string => string|null)
     */
    protected $forbiddenFunctions = array(
             # 1) Discourages the use of alias functions that are kept in PHP for compatibility with older versions.
             'sizeof'          => 'count',
             'delete'          => 'unset',
             'print'           => 'echo',
             'is_null'         => null,
             'create_function' => null,

             # 2) Discourages the use of our own debugging helper methods
             'Clansuite_Debug::printR' => 'null',
             'clansuite_debug::printr' => 'null',
             'Clansuite_Debug::firebug' => 'null',
             'clansuite_debug::firebug' => 'null',

             # 3) Discourages the use of PHP debugging functions
             'print_r'          => 'null',
             'var_dump'         => 'null',
            #'error_log'        => 'null',

              # 4) Discourages the use of normale string functions, thereby enforces the usage of mbstring functions
             'split'           => 'mb_split',        # Split multibyte string using regular expression
             'strcut'          => 'mb_strcut',       # Get part of string
             'trimwidth'       => 'mb_strimwidth',   # Get truncated string with specified width
             'stripos'         => 'mb_stripos',      # Finds position of first occurrence of a string within another, case insensitive
             'stristr'         => 'mb_stristr',      # Finds first occurrence of a string within another, case insensitive
             'strlen'          => 'mb_strlen',       # Get string length
             'strpos'          => 'mb_strpos',       # Find position of first occurrence of string in a string
             'strrchr'         => 'mb_strrichr',     # Finds the last occurrence of a character in a string within another, case insensitive
             'strripos'        => 'mb_strripos',     # Finds position of last occurrence of a string within another, case insensitive
             'strrpos'         => 'mb_strrpos',      # Find position of last occurrence of a string in a string
             'strstr'          => 'mb_strstr',       # Finds first occurrence of a string within another
             'strtolower'      => 'mb_strtolower',   # Make a string lowercase
             'strtoupper'      => 'mb_strtoupper',   # Make a string uppercase
             'strwidth'        => 'mb_strwidth',     # Return width of string
             'substr_count'    => 'mb_substr_count', # Count the number of substring occurrences
             'substr'          => 'mb_substr',       # Get part of string

             # 5) Discourages the use of ereg-functions in general = no ereg*() and no mb_ereg_*()
             'ereg'              => 'null',
             'mb_ereg'           => 'null',
             'eregi'             => 'null',
             'mb_eregi'          => 'null',
             'ereg_replace'      => 'null',
             'mb_ereg_replace'   => 'null',
             'eregi_replace'     => 'null',
             'mb_eregi_replace'  => 'null',
            );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_STRING,
                T_PRINT  #,
               );
    }
}
?>
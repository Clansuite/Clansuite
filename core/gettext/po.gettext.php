class Gettext_PO_File
{
    function _po_clean_helper($x)
    {
        if (is_array($x))
        {
            foreach ($x as $k => $v)
            {
                $x[$k]= _po_clean_helper($v);
            }
        }
        else
        {
            if ($x[0] == '"')
            {
                $x= substr($x, 1, -1);
            }
            
            $x = str_replace("\"\n\"", '', $x);
            $x = str_replace('$', '\\$', $x);
            $x = @ eval ("return \"$x\";");
        }

        return $x;
    }

    /**
     * Reads a Gettext .po file
     *
     * @copyright 2007 Matthias Bauer
     * @author Matthias Bauer
     * @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General Public License 2.1
     *
     * @link http://www.gnu.org/software/gettext/manual/gettext.html#PO-Files
     */
    function read($in)
    {
        # read .po file
        $fc= file_get_contents($in);

        # normalize newlines
        $fc= str_replace( array ("\r\n","\r"),
                          array ("\n", "\n"),
                          $fc);

        # results array
        $hash= array ();

        # temporary array
        $temp= array ();

        # state
        $state= null;
        $fuzzy= false;

        # iterate over lines
        foreach (explode("\n", $fc) as $line)
        {
            $line= trim($line);

            if ($line === '')
            {
                continue;
            }

            list ($key, $data)= explode(' ', $line, 2);

            switch ($key)
            {
                case '#,': # flag...

                    $fuzzy= in_array('fuzzy', preg_split('/,\s*/', $data));
                
                case '#':  # translator-comments

                case '#.': # extracted-comments
                
                case '#:': # reference...

                case '#|': # msgid previous-untranslated-string

                    # start a new entry
                    if (sizeof($temp) && array_key_exists('msgid', $temp) && array_key_exists('msgstr', $temp))
                    {
                        if (!$fuzzy)
                        {
                            $hash[]= $temp;
                        }

                        $temp= array();
                        $state= null;
                        $fuzzy= false;
                    }
                    break;

                case 'msgctxt': # context
                
                case 'msgid': # untranslated-string
                
                case 'msgid_plural': # untranslated-string-plural

                    $state= $key;
                    $temp[$state]= $data;
                    break;

                case 'msgstr': # translated-string

                    $state= 'msgstr';
                    $temp[$state][]= $data;
                    break;
                    
                default :
                    
                    if (strpos($key, 'msgstr[') !== false)
                    {
                        # translated-string-case-n
                        $state= 'msgstr';
                        $temp[$state][]= $data;
                    } 
                    else
                    {
                        # continued lines
                        switch ($state)
                        {
                            case 'msgctxt':

                            case 'msgid':

                            case 'msgid_plural':

                                $temp[$state] .= "\n" . $line;
                                break;

                            case 'msgstr':

                                $temp[$state][sizeof($temp[$state]) - 1] .= "\n" . $line;
                                break;

                            default :

                                # parse error
                                return false;
                        }
                    }
                    break;
            }
        }

        # add final entry
        if ($state == 'msgstr')
        {
            $hash[]= $temp;
        }

        # Cleanup data, merge multiline entries, reindex hash for ksort
        $temp= $hash;
        $hash= array();
        
        foreach ($temp as $entry)
        {
            foreach ($entry as & $v)
            {
                $v= _po_clean_helper($v);

                if ($v === false)
                {
                    # parse error
                    return false;
                }
            }
            $hash[$entry['msgid']]= $entry;
        }

        return $hash;
    }
}
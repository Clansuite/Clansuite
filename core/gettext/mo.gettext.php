class Gettext_MO_File
{
    /**
     * Writes a GNU gettext style machine object (mo-file).
     *
     * @copyright 2007 Matthias Bauer
     * @author Matthias Bauer
     * @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General Public License 2.1
     *
     * @link http://www.gnu.org/software/gettext/manual/gettext.html#MO-Files
     */
    function write($hash, $out)
    {
        # sort by msgid
        ksort($hash, SORT_STRING);

        # our mo file data
        $mo= '';

        # header data
        $offsets= array ();
        $ids= '';
        $strings= '';

        foreach ($hash as $entry)
        {
            $id= $entry['msgid'];

            if (isset ($entry['msgid_plural']))
            {
                $id .= "\x00" . $entry['msgid_plural'];
            }

            # context is merged into id, separated by EOT (\x04)
            if (array_key_exists('msgctxt', $entry))
            {
                $id= $entry['msgctxt'] . "\x04" . $id;
            }

            # plural msgstrs are NUL-separated
            $str= implode("\x00", $entry['msgstr']);

            # keep track of offsets
            $offsets[]= array ( mb_strlen($ids), mb_strlen($id), mb_strlen($strings), mb_strlen($str));

            # plural msgids are not stored (?)
            $ids .= $id . "\x00";

            $strings .= $str . "\x00";
        }

        # keys start after the header (7 words) + index tables ($#hash * 4 words)
        $key_start= 7 * 4 + count($hash) * 4 * 4;

        # values start right after the keys
        $value_start= $key_start +mb_strlen($ids);

        # first all key offsets, then all value offsets
        $key_offsets= array ();
        $value_offsets= array ();

        # calculate

        foreach ($offsets as $v)
        {
            list ($o1, $l1, $o2, $l2)= $v;
            $key_offsets[]= $l1;
            $key_offsets[]= $o1 + $key_start;
            $value_offsets[]= $l2;
            $value_offsets[]= $o2 + $value_start;
        }

        $offsets= array_merge($key_offsets, $value_offsets);

        # write header
        $mo .= pack('Iiiiiii', 0x950412de,          # magic number
                    0,                              # version
                    count($hash),                   # number of entries in the catalog
                    7 * 4,                          # key index offset
                    7 * 4 + count($hash) * 8,       # value index offset,
                    0,                              # hashtable size (unused, thus 0)
                    $key_start                      # hashtable offset
        );

        # offsets
        foreach ($offsets as $offset)
        {
            $mo .= pack('i', $offset);
        }

        # ids
        $mo .= $ids;

        # strings
        $mo .= $strings;

        file_put_contents($out, $mo);
    }
}
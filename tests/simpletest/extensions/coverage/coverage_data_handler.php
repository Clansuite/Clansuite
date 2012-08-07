<?php
/**
 * @package        SimpleTest
 * @subpackage     Extensions
 */

/**
 * Persists code coverage data into SQLite database and aggregate data for convienent
 * interpretation in report generator.  Be sure to not to keep an instance longer
 * than you have, otherwise you risk overwriting database edits from another process
 * also trying to make updates.
 * @package        SimpleTest
 * @subpackage     Extensions
 */
class CoverageDataHandler
{
    public $db;

    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->db = new PDO('sqlite:'. $filename);
        if (empty($this->db)) {
            throw new Exception("Could not create sqlite db ". $filename);
        }
    }

    public function createSchema()
    {
        $this->db->exec("CREATE TABLE untouched (filename text)");
        $this->db->exec("CREATE TABLE coverage (name text, coverage text)");
    }

    public function getFilenames()
    {
        $filenames = array();

        $sql = "SELECT DISTINCT name FROM coverage";
        $stmt = $this->db->query($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $filenames[] = $row['name'];
        }

        return $filenames;
    }

    public function write($coverage)
    {
        foreach ($coverage as $file => $lines) {
            $coverageStr = serialize($lines);
            $relativeFilename = self::ltrim(getcwd() . '/', $file);
            $sql = "INSERT INTO coverage (name, coverage) VALUES ('$relativeFilename', '$coverageStr')";
            // if this fails, check you have write permission
            $this->db->exec($sql);
        }
    }

    public function read()
    {
        $coverage = array_flip($this->getFilenames());
        foreach ($coverage as $file => $garbage) {
            $coverage[$file] = $this->readFile($file);
        }

        return $coverage;
    }

    public function readFile($file)
    {
        $aggregate = array();

        $sql = "SELECT coverage FROM coverage WHERE name = '$file'";
        $query = $this->db->query($sql);
        $query->execute();

        foreach($query as $row) {
            $this->aggregateCoverage($aggregate, unserialize($row[0]));
        }

        return $aggregate;
    }

    public function aggregateCoverage(&$total, $next)
    {
        foreach ($next as $lineno => $code) {
            if (!isset($total[$lineno])) {
                $total[$lineno] = $code;
            } else {
                $total[$lineno] = $this->aggregateCoverageCode($total[$lineno], $code);
            }
        }
    }

    public function aggregateCoverageCode($code1, $code2)
    {
        switch ($code1) {
            case -2: return -2;
            case -1: return $code2;
            default:
                switch ($code2) {
                    case -2: return -2;
                    case -1: return $code1;
                }
        }

        return $code1 + $code2;
    }

    public static function ltrim($cruft, $pristine)
    {
        if (stripos($pristine, $cruft) === 0) {
            return substr($pristine, strlen($cruft));
        }

        return $pristine;
    }

    public function writeUntouchedFile($file)
    {
        $relativeFile = CoverageDataHandler::ltrim('./', $file);
        $sql = "INSERT INTO untouched VALUES ('$relativeFile')";
        $this->db->exec($sql);
    }

    public function readUntouchedFiles()
    {
        $untouched = array();
        $query = $this->db->query("SELECT filename FROM untouched ORDER BY filename");
        $query->execute();
        foreach($query as $row) {
            $untouched[] = $row[0];
        }

        return $untouched;
    }
}

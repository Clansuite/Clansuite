<?php
// $Id$
require_once(dirname(__FILE__) . '/../../../autorun.php');

class CoverageUnitTests extends TestSuite {
    function CoverageUnitTests() {
        $this->TestSuite('Coverage Unit tests');
        $path = __DIR__ . DIRECTORY_SEPARATOR . '*_test.php';
        $files = glob($path);
        foreach($files as $testfile) {
            $this->addFile($testfile);
        }
    }
}
?>
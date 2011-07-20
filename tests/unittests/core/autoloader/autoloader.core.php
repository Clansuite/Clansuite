<?php
interface ThisInterfaceExists
{

}

class ThisClassExists
{

}

class Clansuite_Loader_Test extends Clansuite_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();

        # Test Subject
        require_once TESTSUBJECT_DIR . 'core/autoload/autoloader.core.php';

        if(extension_loaded('APC') === true)
        {
            # reset APC for readAutoloadingMapApc() / writeAutoloadingMapApc()
            apc_clear_cache('user');
        }
    }

    public function tearDown()
    {

    }

    /**
     *  Highly experimental:
     *  - object creation on the fly after session start: works
     *  - what about interface creation on the fly @todo
    public function createClass($name)
    {
        ini_set('unserialize_callback_func', 'Clansuite_Loader_Test::unserialize_callback_method_class');

        $this->object = unserialize(sprintf('O:%d:"%s":0:{}', strlen($name), $name));

        return $this->object;
    }

    public static function unserialize_callback_method_class($classname)
    {
        eval ('class ' . $classname . '() {}');
    }

    public function testUnitTestHelper_createClass()
    {
        $class = $this->createClass('MyClass');
        $this->assertIsA($class, 'MyClass');
        unset($class);
    }
    */

    /**
     * testMethod_autoload_exitsWhenClassOrInterfaceExist()
     */
    public function testMethod_autoload_exitsWhenClassOrInterfaceExist()
    {
        # Class already loaded
        $this->assertTrue(Clansuite_Loader::autoload('ThisClassExists'));

        # Interface already loaded
        $this->assertIdentical(interface_exists('ThisInterfaceExists') ,
                               Clansuite_Loader::autoload('ThisInterfaceExists'));
    }

    /**
     * testMethod_autoload()
     */
    public function testMethod_autoload()
    {
        # try to load a class which is excluded, e.g. "Smarty_Internal" classes
        #$this->assertFalse(Clansuite_Loader::autoload(''));
    }

    /**
     * testMethod_autoloadExclusions()
     */
    public function testMethod_autoloadExclusions()
    {
        # exclude "Cs" classes
        $this->assertTrue(Clansuite_Loader::autoloadExclusions('Cs_SomeClass'));

        # exclude "Smarty_Internal" classes
        $this->assertTrue(Clansuite_Loader::autoloadExclusions('Smarty_Internal_SomeClass'));

        # exclude "Doctrine" classes
        $this->assertTrue(Clansuite_Loader::autoloadExclusions('Doctrine_SomeClass'));

        # but not, our own doctrine classes "Clansuite_Doctrine_"
        $this->assertFalse(Clansuite_Loader::autoloadExclusions('Clansuite_Doctrine_SomeClass'));

        # exclude "Smarty" classes
        $this->assertTrue(Clansuite_Loader::autoloadExclusions('Smarty_'));

        # but not, our own smarty class "Clansuite_Renderer_Smarty"
        $this->assertFalse(Clansuite_Loader::autoloadExclusions('Clansuite_Renderer_Smarty'));
    }

    /**
     * testMethod_autoloadInclusions()
     */
    public function testMethod_autoloadInclusions()
    {
        # try to load an unknown class
        $this->assertFalse(Clansuite_Loader::autoloadInclusions('SomeUnknownClass'));

        # try to load "Clansuite_Staging" class
        # @todo class already exists... :D
        #$this->assertTrue(Clansuite_Loader::autoloadInclusions('Clansuite_Staging'));
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * testMethod_autoloadByApcOrFileMap
     */
    public function testMethod_autoloadByApcOrFileMap()
    {
        # try to load an unknown class
        $this->assertFalse(Clansuite_Loader::autoloadByApcOrFileMap('SomeUnknownClass'));

        # try to load "Clansuite_Eventdispatcher" class
        # which is expected to be inside the classmap file
        
        # @todo disabled for now - i am not sure how to test...
        # way to go
        # 1) pseudo map files for apc and filecache
        # 2) dynamically unload apc extension to test filemap caching?
        #$this->assertTrue(Clansuite_Loader::autoloadByApcOrFileMap('Clansuite_Eventdispatcher'));
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * testMethod_autoloadIncludePath()
     */
    public function testMethod_autoloadIncludePath()
    {
        # try to load an unknown class
        $this->assertFalse(Clansuite_Loader::autoloadIncludePath('\Namespace\Library\Clansuite_SomeUnknown_Class'));

        $this->markTestIncomplete('Currently no namespaced Clansuite classes available.');

        # try to load existing namespaced class
        #$this->assertTrue(Clansuite_Loader::autoloadIncludePath('\Namespace\Library\Clansuite_Class'));
   }

    /**
     * testMethod_autoloadTryPathsAndMap()
     */
    public function testMethod_autoloadTryPathsAndMap()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
        
        # try to load a class from core path - clansuite/core/class_name.core.php
        #$this->assertTrue(Clansuite_Loader::autoloadTryPathsAndMap('Clansuite_Router'));

        # try to load a class from events path - clansuite/core/events/classname.class.php
        #$this->assertTrue(Clansuite_Loader::autoloadTryPathsAndMap('BlockIps'));

        # try to load a class from filter path - clansuite/core/filters/classname.filter.php
        #$this->assertTrue(Clansuite_Loader::autoloadTryPathsAndMap('Clansuite_Filter_HtmlTidy'));

        # try to load a class from viewhelper path - clansuite/core/viewhelper/classname.core.php
        #$this->assertTrue(Clansuite_Loader::autoloadTryPathsAndMap('Clansuite_Theme'));
    }

    public function testMethod_writeAutoloadingMapFile()
    {
        $classmap_file = ROOT_CONFIG . 'autoloader.classmap.php';
        if(is_file($classmap_file))
        {
            unlink($classmap_file);
        }
        # file will be created
        $this->assertIdentical(array(), Clansuite_Loader::readAutoloadingMapFile());
        $this->assertTrue(is_file($classmap_file));

        $array = array ( 'class' => 'file' );
        $this->assertTrue(Clansuite_Loader::writeAutoloadingMapFile($array));
        $this->assertIdentical($array, Clansuite_Loader::readAutoloadingMapFile());
    }

    public function testMethod_readAutoloadingMapFile()
    {
        $classmap_file = ROOT_CONFIG . 'autoloader.classmap.php';
        if(is_file($classmap_file))
        {
            unlink($classmap_file);
        }
        # file will be created
        $this->assertIdentical(array(), Clansuite_Loader::readAutoloadingMapFile());
        $this->assertTrue(is_file($classmap_file));

        $array = array ( 'class' => 'file' );
        $this->assertTrue(Clansuite_Loader::writeAutoloadingMapFile($array));
        $this->assertIdentical($array, Clansuite_Loader::readAutoloadingMapFile());
    }

    public function testMethod_writeAutoloadingMapApc()
    {
        if(extension_loaded('apc'))
        {
            $array = array ( 'class' => 'file' );
            $this->assertTrue(Clansuite_Loader::writeAutoloadingMapApc($array));
            $this->assertIdentical($array, Clansuite_Loader::readAutoloadingMapApc());
        }
    }

    public function testMethod_readAutoloadingMapApc()
    {
        if(extension_loaded('apc'))
        {
            $this->assertIdentical(apc_fetch('CLANSUITE_CLASSMAP'), Clansuite_Loader::readAutoloadingMapApc());
        }
    }

    public function testMethod_addToMapping()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testMethod_includeFileAndMap()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testMethod_requireFile()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testMethod_loadLibrary()
    {
        $this->assertTrue(Clansuite_Loader::loadLibrary('snoopy'));
    }
}
?>
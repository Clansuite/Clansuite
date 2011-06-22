<?php

class Clansuite_Feed_Test extends Clansuite_UnitTestCase
{
    # valid rss feed source
    const FEED_URL = 'http://groups.google.com/group/clansuite/feed/rss_v2_0_msgs.xml';

    public function setUp()
    {
        parent::setUp();

        # Test Subject
        require_once TESTSUBJECT_DIR . 'core/feed.core.php';

        # Dependency
        require_once TESTSUBJECT_DIR . 'core/bootstrap/clansuite.loader.php';
    }

    /**
     * testMethod_fetchRSS()
     */
    public function testMethod_fetchRSS()
    {
        $simplepie_feed_object = Clansuite_Feed::fetchRSS(self::FEED_URL);

        $this->assertIsA($simplepie_feed_object, 'SimplePie');
    }

    /**
     * testMethod_fetchRawRSS_withoutCaching()
     */
    public function testMethod_fetchRawRSS_withoutCaching()
    {
        $feedcontent = Clansuite_Feed::fetchRawRSS(self::FEED_URL, false);

        $this->assertContainsString('title>clansuite.com Google Group</title>', $feedcontent);
    }

    /**
     * testMethod_fetchRawRSS_withCaching()
     */
    public function testMethod_fetchRawRSS_withCaching()
    {
        $feedcontent = Clansuite_Feed::fetchRawRSS(self::FEED_URL, true);

        # check for cache file
        $this->assertTrue(is_file(ROOT_CACHE . md5(self::FEED_URL)));

        # check for content
        $this->assertContainsString('title>clansuite.com Google Group</title>', $feedcontent);
    }

    /**
     * testMethod_getFeedcreator()
     */
    public function testMethod_getFeedcreator()
    {
        $feedcreator_object = Clansuite_Feed::getFeedcreator();

        $this->assertIsA($feedcreator_object, 'Feedcreator');
    }
}
?>
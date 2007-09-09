<?php
require_once(dirname(__FILE__) . '/locator.php');

class InterfaceCache {
	static private $path;
	static private $cache;
	
	static function setPath($path) {
		self::$path = $path;
		self::refresh();
	}
	
	static function refresh() {
		$serialised = @file_get_contents(self::$path);
		self::$cache = $serialised ? unserialize($serialised) : array();
	}
	
	static function clear() {
		@unlink(self::$path);
	}
	
	static function getInterfaces($source) {
		$source = realpath($source);
		if (! isset(self::$cache[$source])) {
			return false;
		}
		if (self::$cache[$source]->getTimestamp() < self::timestamp($source)) {
			return false;
		}
		return self::$cache[$source]->getInterfaces();
	}
	
	static function setInterfaces($source, $interfaces) {
		$source = realpath($source);
		self::$cache[$source] = new SourceFile($interfaces, self::timestamp($source));
		file_put_contents(self::$path, serialize(self::$cache));
	}
	
	static function timestamp($file) {
		$statistics = @stat($file);
		return isset($statistics['mtime']) ? $statistics['mtime'] : false;
	}
}

class SourceFile {
	private $interfaces;
	private $timestamp;
	
	function __construct($interfaces, $timestamp) {
		$this->interfaces = $interfaces;
		$this->timestamp = $timestamp;
	}
	
	function getInterfaces() {
		return $this->interfaces;
	}
	
	function getTimestamp() {
		return $this->timestamp;
	}
}

class LazyInclude extends LocatorDecorator implements PhemtoLocator {
    private $source;

    function __construct($service, $source) {
        parent::__construct($service);
        $this->source = $source;
    }

	function getInterfaces() {
		if (InterfaceCache::getInterfaces($this->source) === false) {
			require_once($this->source);
			InterfaceCache::setInterfaces($this->source, parent::getInterfaces());
		}
		return InterfaceCache::getInterfaces($this->source);
	}
	
	function getReflection() {
		require_once($this->source);
		return parent::getReflection();
	}
	
	function instantiate($dependencies) {
		require_once($this->source);
		return parent::instantiate($dependencies);
	}
}
?>
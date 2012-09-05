<?php
$classLoader = new \Doctrine\Common\ClassLoader('Entity', realpath('../Doctrine'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Repository', realpath('../Doctrine'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Proxy', realpath('../Doctrine'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL\Migrations', realpath('../Doctrine/Migrations'));
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);

$driverImpl = $config->newDefaultAnnotationDriver(array(realpath('../Doctrine/Entity')));
$config->setMetadataDriverImpl($driverImpl);

$chainDriverImpl = new \Doctrine\ORM\Mapping\Driver\DriverChain();
$yourDefaultDriverImpl = new \Doctrine\ORM\Mapping\Driver\YamlDriver('../Doctrine/Yaml');
$chainDriverImpl->addDriver($yourDefaultDriverImpl, 'Entity');

$config->setMetadataDriverImpl($chainDriverImpl);

$config->setProxyDir('../Dctrine/Proxy');
$config->setProxyNamespace('Proxy');

$connectionOptions = array(
            'driver'    => 'pdo_mysql',
            'user'      => 'root',
            'password'  => '',
            'dbname'    => 'clansuite',
            'host'      => 'localhost'
        );

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
$db = $em->getConnection();

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($db),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em),
    'dialog' => new \Symfony\Component\Console\Helper\DialogHelper(),
));

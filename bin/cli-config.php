<?php
$classLoader = new \Doctrine\Common\ClassLoader('Entities', realpath('../doctrine'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Repositories', realpath('../doctrine'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Proxies', realpath('../doctrine'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL\Migrations', realpath('../doctrine/migrations'));
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$driverImpl = $config->newDefaultAnnotationDriver(array(realpath('../doctrine/entities')));
$config->setMetadataDriverImpl($driverImpl);
$config->setProxyDir('../doctrine/proxies');
$config->setProxyNamespace('Proxies');

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

?>
<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\EventManager as EventManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Configuration;
use Doctrine\Common\Cache\ArrayCache as Cache;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\ClassLoader;

use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;

$cache = new Doctrine\Common\Cache\ArrayCache;

$annotationReader = new Doctrine\Common\Annotations\AnnotationReader;
$cachedAnnotationReader = new Doctrine\Common\Annotations\CachedReader(
    $annotationReader, // use reader
    $cache // and a cache driver
);

$driverChain = new Doctrine\ORM\Mapping\Driver\DriverChain();
// load superclass metadata mapping only, into driver chain
// also registers Gedmo annotations.NOTE: you can personalize it
Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM(
    $driverChain, // our metadata driver chain, to hook into
    $cachedAnnotationReader // our cached annotation reader
);

// now we want to register our application entities,
// for that we need another metadata driver used for Entity namespace
$annotationDriver = new Doctrine\ORM\Mapping\Driver\AnnotationDriver(
    $cachedAnnotationReader, // our cached annotation reader
    array(__DIR__ . DIRECTORY_SEPARATOR . '../src')
);
// NOTE: driver for application Entity can be different, Yaml, Xml or whatever
// register annotation driver for our application Entity namespace
$driverChain->addDriver($annotationDriver, 'Api');

$config = new Doctrine\ORM\Configuration;
$config->setProxyDir('/tmp');
$config->setProxyNamespace('Proxy');
$config->setAutoGenerateProxyClasses(true); // this can be based on production config.
// register metadata driver
$config->setMetadataDriverImpl($driverChain);
// use our allready initialized cache driver
$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);

AnnotationRegistry::registerFile(__DIR__. DIRECTORY_SEPARATOR . '../vendor' . DIRECTORY_SEPARATOR . 'doctrine' . DIRECTORY_SEPARATOR . 'orm' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Doctrine' . DIRECTORY_SEPARATOR . 'ORM' . DIRECTORY_SEPARATOR . 'Mapping' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'DoctrineAnnotations.php');

// Third, create event manager and hook prefered extension listeners
$evm = new Doctrine\Common\EventManager();
// gedmo extension listeners

// sluggable
$sluggableListener = new Gedmo\Sluggable\SluggableListener;
// you should set the used annotation reader to listener, to avoid creating new one for mapping drivers
$sluggableListener->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($sluggableListener);


//getting the EntityManager
$em = EntityManager::create(
    array(
        'driver'  => 'pdo_mysql',
        'host'    => 'localhost',
        'port'    => '3306',
        'user'    => 'root',
        'password'  => 'root',
        'dbname'  => 'code_education_silex',
    ),
    $config,
    $evm
);

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ .'/views',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

//Configura o encoder para criptografar a plainPassword do usuario
/*
$app['user_encoder'] = $app->share(function($app) use ($em) {
    $user = new Api\User\UserProvider($em);
//var_dump($app['security.encoder_factory']);    
    $user->setPasswordEncoder($app['security.encoder_factory']->getEncoder($user));
    return $user;
});
*/
/*
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'anonymous' => true,
            'pattern' => '^/admin',
            'form' => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
            'users' => $app->share(function() use ($em) {
                return new Api\User\UserProvider($em);
            }),
            'logout' => array('logout_path' => '/admin/logout', 'invalidate_session' => true),
        ),
    )
));

// access controls
$app['security.access_rules'] = array(
    array('^/admin', 'ROLE_ADMIN'),
    array('^.*$', 'ROLE_USER'),
);*/

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'login_path' => array(
            'pattern' => '^/login$',
            'anonymous' => true
        ),
        'default' => array(
            'pattern' => '^/.*$',
            'anonymous' => true,
            'form' => array(
                'login_path' => '/login',
                'check_path' => '/login_check',
            ),
            'logout' => array(
                'logout_path' => '/logout',
                'invalidate_session' => false
            ),
            'users' => $app->share(function($app) use ($em) { 
               return new Api\User\UserProvider($em);
            }),
        )
    ),
    'security.access_rules' => array(
        array('^/login$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
        array('^/.+$', 'ROLE_ADMIN')
    )
));

return $app;

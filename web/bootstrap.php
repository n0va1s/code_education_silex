<?php

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(),array(
    'twig.path' => __DIR__ .'/views',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

/*
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'anonymous' => true,
            'pattern' => '^/',
            'form' => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
            // lazily load the user_repository
            'users' => $app->share(function () use ($app) {
                return $app['user_repository'];
            }),
            'logout' => array('logout_path' => '/admin/logout'),
        ),
    )
));
// access controls
$app['security.access_rules'] = array(
    array('^/admin', 'ROLE_ADMIN'),
);
*/
return $app;

<?php

    require_once __DIR__.'/bootstrap.php';

    use Symfony\Component\HttpKernel\Debug\ErrorHandler;

    ErrorHandler::register();

    $app = new Silex\Application();

    /**
     * Register Service Providers
     */
    $app->register( new Silex\Provider\TwigServiceProvider(), array( 'twig.path' => __DIR__.'/views' ) );

    $app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/config/config.json"));

    if($app['debug']) {
        $app->register(new Whoops\Provider\Silex\WhoopsServiceProvider);
    }

    $app->register( new Silex\Provider\DoctrineServiceProvider(),
        array( $app['db.options'] )
    );

    /**
     * Mount further Controller Providers
     */
    $app->mount( '/', new kino\cinemaControllerProvider() );

    $app->mount( '/scrape', new kino\scrapeControllerProvider() );

    $app->mount( '/api', new kino\apiControllerProvider() );

    return $app;

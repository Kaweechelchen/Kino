<?php

    // Thierry Degeling [@Kaweechelchen]

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    date_default_timezone_set( 'Europe/Luxembourg' );

    require_once __DIR__.'/bootstrap.php';

    $app = new Silex\Application();

    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/views',
    ));

    $app->mount( '/api/1/', new kino\apiControllerProvider() );

    $app->mount( '/scrape', new kino\scrapeControllerProvider() );

    $app->get('/api', function () use ( $app ) {
        return $app->redirect('/api/1/');
    });

    $app->mount( '/', new kino\viewControllerProvider() );

    $app->after(function (Request $request, Response $response) {

        $response->headers->set('Access-Control-Allow-Origin', '*');

    });

    return $app;

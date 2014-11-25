<?php

    // Thierry Degeling [@Kaweechelchen]

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    date_default_timezone_set( 'Europe/Luxembourg' );

    require_once __DIR__.'/bootstrap.php';

    $app = new Silex\Application();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/views',
    ));

    $app->mount( '/scrape', new kino\scrapeControllerProvider() );

    $app->mount( '/', new kino\viewControllerProvider() );

    $app->after(function (Request $request, Response $response) {

        $response->headers->set('Access-Control-Allow-Origin', '*');

    });

    return $app;

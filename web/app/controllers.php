<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use AgenDAV\DateHelper;

// Trust configured proxies
Request::setTrustedProxies($app['proxies']);

$app->get('/', function () use ($app) {
    $app['session']->set('jeje', 'jiji');
    return $app['twig']->render(
        'calendar.html',
        [
            'stylesheets' => $app['stylesheets'],
            'print_stylesheets' => $app['print.stylesheets'],
            'scripts' => [],
        ]
    );
})
->bind('calendar');

$app->get('/preferences', function () use ($app) {
    return $app['twig']->render(
        'preferences.html',
        [
            'stylesheets' => $app['stylesheets'],
            'print_stylesheets' => $app['print.stylesheets'],
            'scripts' => [],
            'available_timezones' => DateHelper::getAllTimeZones(),
            'timezone' => 'Europe/Madrid',
            'calendars' => [],
        ]
    );
})
->bind('preferences');

$app->get('/logout', function () use ($app) {
})
->bind('logout');

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html',
        'errors/'.substr($code, 0, 2).'x.html',
        'errors/'.substr($code, 0, 1).'xx.html',
        'errors/default.html',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});

<?php

$app = new \Silex\Application();

$app->register(new \Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__ .'/views',
  'twig.options' => array(
    'cache' =>  __DIR__ .'/../cache'
  )
));

$app->register(new \lastcall\GeoIp2\Silex\GeoIp2ServiceProvider(), array(
  'geoip.db' => __DIR__ . '/../data/GeoLite2-City.mmdb',
));

$app->register(new \Knp\Provider\ConsoleServiceProvider(), array(
  'console.name' => 'GeoIP2',
  'console.version' => '0.1.0',
  'console.project_directory' => __DIR__ . '/..'
));

$app->extend('console', function(\Knp\Console\Application $console) {
  $console->add(new \lastcall\GeoIp2\Silex\RefreshDbCommand());
  return $console;
});

$app->register(new \Silex\Provider\ServiceControllerServiceProvider());

$app['legacyjs.controller'] = $app->share(function() use ($app) {
  return new \lastcall\GeoIp2\Silex\LegacyJsController($app['geoip'], $app['twig']);
});
$app->get('/country.js', 'legacyjs.controller:countryJSAction');
$app->get('/city.js', 'legacyjs.controller:cityJSAction');

return $app;
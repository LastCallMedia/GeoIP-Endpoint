<?php

namespace lastcall\GeoIp2\Silex;

use Silex\Application;
use Silex\ServiceProviderInterface;

class GeoIp2ServiceProvider implements ServiceProviderInterface {

  public function register(Application $app) {

    $app['geoip'] = $app->share(function() use ($app) {
      if($app['geoip.db'] && file_exists($app['geoip.db'])) {
        // Register the closure.
        $app->finish(function() use ($app) {
          $app['geoip']->close();
        });
        return new \GeoIp2\Database\Reader($app['geoip.db']);
      }
      return new \lastcall\GeoIp2\NullProvider();
    });
  }

  public function boot(Application $app) {
    // Nothing.
  }
}
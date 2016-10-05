<?php

namespace lastcall\GeoIp2\Silex;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class GeoIp2ServiceProvider implements ServiceProviderInterface, EventListenerProviderInterface{

  private $reader;

  public function register(Container $app) {

    $app['geoip.db.require'] = FALSE;
    $app['geoip.db'] = FALSE;

    $app['geoip'] = function() use ($app) {
      if($app['geoip.db'] && file_exists($app['geoip.db'])) {
        return $this->reader = new \GeoIp2\Database\Reader($app['geoip.db']);
      }
      if($app['geoip.db.require'] === TRUE) {
        throw new \Exception("GeoIP Database is not found.");
      }
      return new \lastcall\GeoIp2\NullProvider();
    };
  }

  public function subscribe(Container $container, EventDispatcherInterface $dispatcher) {
    $dispatcher->addListener(KernelEvents::TERMINATE, function() use ($container) {
      if(isset($this->reader)) {
        $this->reader->close();
      }
    });
  }

}
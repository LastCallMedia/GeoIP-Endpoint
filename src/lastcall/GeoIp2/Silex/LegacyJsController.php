<?php

namespace lastcall\GeoIp2\Silex;

use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\Controller;

class LegacyJsController extends Controller {


  public function __construct(ProviderInterface $geoip, \Twig_Environment $twig) {
    $this->geoip = $geoip;
    $this->twig = $twig;
  }

  public function countryJSAction(Request $request) {
    $request->setRequestFormat('js');
    $ip = $request->getClientIp();

    try {
      $record = $this->geoip->city($ip);

      return $this->twig->render('country.twig.js', array(
        'country_code' => $record->country->isoCode,
        'country_name' => $record->country->name,
      ));
    }
    catch(AddressNotFoundException $exception) {
      return $this->twig->render('country.twig.js', array(
        'country_code' => '',
        'country_name' => '',
      ));
    }
  }

  public function cityJSAction(Request $request) {
    $request->setRequestFormat('js');
    $ip = $request->getClientIp();

    try {
      $record = $this->geoip->city($ip);

      return $this->twig->render('response.twig.js', array(
        'city_name' => $record->city->name,
        'region' => $record->mostSpecificSubdivision->isoCode,
        'region_name' => $record->mostSpecificSubdivision->name,
        'postal_code' => $record->postal->code,
        'country_code' => $record->country->isoCode,
        'country_name' => $record->country->name,
        'latitude' => $record->location->latitude,
        'longitude' => $record->location->longitude,
      ));
    }
    catch(AddressNotFoundException $exception) {
      return $this->twig->render('response.twig.js', array(
        'city_name' => '',
        'region' => '',
        'region_name' => '',
        'postal_code' => '',
        'country_code' => '',
        'country_name' => '',
        'latitude' => '',
        'longitude' => '',
      ));
    }
  }

}
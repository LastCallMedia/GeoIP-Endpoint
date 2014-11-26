<?php


namespace lastcall\GeoIp2;

use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\ProviderInterface;

class NullProvider implements ProviderInterface {

  public function city($ipAddress) {
    throw new AddressNotFoundException();
  }

  public function country($ipAddress) {
    throw new AddressNotFoundException();
  }
}
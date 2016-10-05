<?php

namespace lastcall\GeoIp2\Silex;

use GuzzleHttp\Client;
use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshDbCommand extends Command {

  protected function configure() {
    $this->setName('refreshdb')
      ->setDescription('Refresh the GeoIP2 database file');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $client = new Client();
    $tmpfile = tempnam(sys_get_temp_dir(), 'geoip2-db');
    $output->writeln('Downloading new database ...');

    try {
      $client->request('GET', 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz', [
        'sink' => $tmpfile
      ]);
      $output->writeln('Download successful.  Unzipping...');
      $app = $this->getSilexApplication();
      exec('gunzip -c ' . escapeshellarg($tmpfile) . ' > ' . escapeshellarg($app['geoip.db']));
      $output->writeln('Unzip successful.  Update complete.');
      return 0;
    }
    catch(\Exception $e) {
      $output->writeln(sprintf('There was a problem downloading the file: %s', $e->getMessage()));
      return 1;
    }
  }
}
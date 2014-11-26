<?php

namespace lastcall\GeoIp2\Silex;

use Guzzle\Http\Client;
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
    $request = $client->get('http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz');
    $request->setResponseBody($tmpfile);

    try {
      $response = $request->send();
      if($response->isSuccessful()) {
        $output->writeln('Download successful.  Unzipping...');
        $app = $this->getSilexApplication();
        $db = realpath($app['geoip.db']);
        exec('gunzip -c ' . escapeshellarg($tmpfile) . ' > ' . escapeshellarg($db));
        $output->write('Unzip successful.  Update complete.');
        return 0;
      }
      $output->writeln(sprintf('There was a problem donloading the file: unspecified error'));
      return 1;
    }
    catch(\Exception $e) {
      $output->writeln(sprintf('There was a problem donloading the file: %s', $e->getMessage()));
      return 1;
    }
  }
}
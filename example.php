#!/usr/bin/php
<?php
declare(strict_types=1);

use GuzzleHttp\Client;
use Miklcct\Nwst\ApiFactory;
use Miklcct\Nwst\Model\Eta;
use Miklcct\Nwst\Model\NoEta;
use Miklcct\Nwst\Model\Rdv;

require __DIR__ . '/vendor/autoload.php';

$api = (new ApiFactory(new Client()))();
$result = $api->getEtaList('970', 1, 2392, Rdv::parse('970-SOU-1'))->wait();

if ($result instanceof NoEta) {
    echo $result->format === 'HTML' ? strip_tags($result->content) : $result->content;
    echo "\n";
} else {
    foreach ($result as $item) {
        assert($item instanceof Eta);
        echo $item->time->format('Y-m-d H:i:s') . "\n";
    }
}
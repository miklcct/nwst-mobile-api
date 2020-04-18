#!/usr/bin/php
<?php
declare(strict_types=1);

use GuzzleHttp\Client;
use Miklcct\Nwst\Api;
use Miklcct\Nwst\ApiFactory;
use Miklcct\Nwst\Model\Eta;
use Miklcct\Nwst\Model\NoEta;
use Miklcct\Nwst\Model\Route;
use Miklcct\Nwst\Model\RouteStop;
use Miklcct\Nwst\Model\StopInfo;
use Miklcct\Nwst\Model\Variant;

require __DIR__ . '/vendor/autoload.php';

$api = (new ApiFactory(new Client()))(Api::ENGLISH);

/** @var Route[] $routes */
$routes = $api->getRouteList()->wait();
$route = $routes[array_rand($routes)];

/** @var Variant[] $variants */
$variants = $api->getVariantList($route->id)->wait();
$variant = $variants[array_rand($variants)];

/** @var RouteStop[] $stops */
$stops = $api->getStopList($variant->info)->wait();
$stop = $stops[array_rand($stops)];
/** @var StopInfo $stop_info */
$stop_info = $api->getRouteInStopList($stop->stopId)->wait();

/** @var Eta[]|NoEta $etas */
$etas = $api->getEtaList($route->routeNumber, $stop->sequence, $stop->stopId, $variant->rdv, $route->bound)->wait();

echo "$stop_info->name ($stop_info->standId)\n\n";
echo "Routes serving this stop:\n";
foreach ($stop_info->routesInService as $route_in_stop) {
    echo sprintf(
        "%s (%s %s %s)\n"
        , $route_in_stop->routeNumber
        , $route_in_stop->origin
        , $route_in_stop->numberOfWays === 0 ? '↺' : '→', $route_in_stop->destination
    );
}
echo "\nThe following routes have no upcoming departures from the terminus:\n";
foreach ($stop_info->routesNotInService as $route_in_stop) {
    echo sprintf(
        "%s (%s %s %s)\n"
        , $route_in_stop->routeNumber
        , $route_in_stop->origin
        , $route_in_stop->numberOfWays === 0 ? '↺' : '→', $route_in_stop->destination
    );
}
echo "\n";
if ($etas instanceof NoEta) {
    echo "The ETA for {$route->routeNumber} towards {$route->destination} is not available:\n";
    echo $etas->format === NoEta::FORMAT_HTML ? strip_tags($etas->content) : $etas->content;
    echo "\n";
} else {
    echo "The upcoming departures for {$route->routeNumber} towards {$route->destination}:\n";
    foreach ($etas as $eta) {
        echo $eta->time->format('H:i:s') . "\t$eta->destination\t$eta->description\n";
    }
}
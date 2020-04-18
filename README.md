# Citybus / NWFB mobile API retriever

This library provides an interface for the Citybus / NWFB mobile API.

The simplest way to create an instance of the API is to use the factory
which automatically retrieves the secret from the maintainer.

```php
use Miklcct\Nwst\ApiFactory;
use GuzzleHttp\Client;
$api = (new ApiFactory(new Client()))();
```

The following 5 APIs are provided with example usage shown below. All APIs work asynchronously.
The return types of the API is stated as phpDoc below.

If the API fails, an exception of type `Miklcct\Nwst\ApiException` is thrown.
```php
use Miklcct\Nwst\Model\Eta;
use Miklcct\Nwst\Model\NoEta;
use Miklcct\Nwst\Model\Rdv;
use Miklcct\Nwst\Model\Route;
use Miklcct\Nwst\Model\RouteStop;
use Miklcct\Nwst\Model\StopInfo;
use Miklcct\Nwst\Model\Variant;
use Miklcct\Nwst\Model\VariantInfo;

/** @var Route[] $routes */
$routes = $api->getRouteList()->wait();
/** @var Variant[] $variants */
$variants = $api->getVariantList('970--So_Uk')->wait();
/** @var RouteStop[] $stops */
$stops = $api->getStopList(
    new VariantInfo(
        Route::COMPANY_NWFB
        , Rdv::parse('970-SOU-1')
        , 1
        , 40
        , 10632
        , Route::INBOUND
    )
)->wait();
/** @var StopInfo $stop_info */
$stop_info = $api->getRouteInStopList(1554)->wait();
/** @var Eta[]|NoEta $etas */
$etas = $api->getEtaList('970', 32, 1554, Rdv::parse('970-SOU-1'), 'O')->wait();
```

An example program using this API is provided as `example.php` here.
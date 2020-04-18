<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Model;

class RouteInStop {
    public const IN_SERVICE = 1;
    public const NOT_IN_SERVICE = 2;

    public function __construct(
        string $company
        , string $routeNumber
        , string $destination
        , float $adultFare
        , int $numberOfWays
        , string $standId
        , string $routeId
        , int $brand
        , int $serviceStatus
        , string $origin
        , int $defaultVariant
        , string $bound
        , int $sequence
        , Rdv $rdv
        , int $fromStop
        , int $toStop
        , string $variantDescription
        , int $variantSerial
    ) {
        $this->company = $company;
        $this->routeNumber = $routeNumber;
        $this->destination = $destination;
        $this->adultFare = $adultFare;
        $this->numberOfWays = $numberOfWays;
        $this->standId = $standId;
        $this->routeId = $routeId;
        $this->brand = $brand;
        $this->serviceStatus = $serviceStatus;
        $this->origin = $origin;
        $this->defaultVariant = $defaultVariant;
        $this->bound = $bound;
        $this->sequence = $sequence;
        $this->rdv = $rdv;
        $this->fromStop = $fromStop;
        $this->toStop = $toStop;
        $this->variantDescription = $variantDescription;
        $this->variantSerial = $variantSerial;
    }

    /**
     * @var string
     */
    public $company;
    /**
     * @var string
     */
    public $routeNumber;
    /**
     * @var string
     */
    public $destination;
    /**
     * @var float
     */
    public $adultFare;
    /**
     * @var int
     */
    public $numberOfWays;
    /**
     * @var string
     */
    public $standId;
    /**
     * @var string
     */
    public $routeId;
    /**
     * @var int
     */
    public $brand;
    /**
     * @var int
     */
    public $serviceStatus;
    /**
     * @var string
     */
    public $origin;
    /**
     * @var int
     */
    public $defaultVariant;
    /**
     * @var string
     */
    public $bound;
    /**
     * @var int
     */
    public $sequence;
    /**
     * @var Rdv
     */
    public $rdv;
    /**
     * @var int
     */
    public $fromStop;
    /**
     * @var int
     */
    public $toStop;
    /**
     * @var string
     */
    public $variantDescription;
    /**
     * @var int
     */
    public $variantSerial;
}
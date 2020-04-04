<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Model;

class RouteStop {
    public const ROLE_START = 'S';
    public const ROLE_EN_ROUTE = 'O';
    public const ROLE_END = 'E';

    public function __construct(
        Rdv $rdv
        , int $sequence
        , int $stopId
        , string $standId
        , float $latitude
        , float $longitude
        , string $stopName
        , string $destination
        , string $role
        , float $adultFare
        , float $childFare
        , float $seniorFare
        , ?string $jointCompany
        , string $routeId
    ) {
        $this->rdv = $rdv;
        $this->sequence = $sequence;
        $this->stopId = $stopId;
        $this->standId = $standId;
        $this->stopName = $stopName;
        $this->destination = $destination;
        $this->role = $role;
        $this->adultFare = $adultFare;
        $this->childFare = $childFare;
        $this->seniorFare = $seniorFare;
        $this->jointCompany = $jointCompany;
        $this->routeId = $routeId;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @var Rdv
     */
    public $rdv;
    /**
     * @var int
     */
    public $sequence;
    /**
     * @var int
     */
    public $stopId;
    /**
     * @var string
     */
    public $standId;
    /**
     * @var float
     */
    public $latitude;
    /**
     * @var float
     */
    public $longitude;
    /**
     * @var string
     */
    public $stopName;
    /**
     * @var string
     */
    public $destination;
    /**
     * @var string
     */
    public $role;
    /**
     * @var float
     */
    public $adultFare;
    /**
     * @var float
     */
    public $childFare;
    /**
     * @var float
     */
    public $seniorFare;
    /**
     * @var string|null
     */
    public $jointCompany;
    /**
     * @var string
     */
    public $routeId;
}
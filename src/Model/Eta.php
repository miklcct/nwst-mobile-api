<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Model;

use DateTimeInterface;

class Eta {
    public const STATUS_ESTIMATED = 'E';
    public const STATUS_SCHEDULED = 'S';

    public function __construct(
        string $routeNumber
        , int $numberOfWays
        , string $standId
        , string $routeId
        , string $bound
        , ?int $distance
        , DateTimeInterface $time
        , string $etaColour
        , DateTimeInterface $queriedTime
        , bool $isRealTime
        , string $providingCompany
        , string $message
        , string $description
        , string $congestionColour
        , Rdv $rdv
        , string $status
        , string $destination
    ) {
        $this->routeNumber = $routeNumber;
        $this->numberOfWays = $numberOfWays;
        $this->standId = $standId;
        $this->routeId = $routeId;
        $this->bound = $bound;
        $this->distance = $distance;
        $this->time = $time;
        $this->etaColour = $etaColour;
        $this->queriedTime = $queriedTime;
        $this->isRealTime = $isRealTime;
        $this->providingCompany = $providingCompany;
        $this->message = $message;
        $this->description = $description;
        $this->congestionColour = $congestionColour;
        $this->rdv = $rdv;
        $this->status = $status;
        $this->destination = $destination;
    }

    /**
     * @var string
     */
    public $routeNumber;
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
     * @var string
     */
    public $bound;
    /**
     * @var int|null
     */
    public $distance;
    /**
     * @var DateTimeInterface
     */
    public $time;
    /**
     * @var string
     */
    public $etaColour;
    /**
     * @var DateTimeInterface
     */
    public $queriedTime;
    /**
     * @var bool
     */
    public $isRealTime;
    /**
     * The company which provides the ETA, e.g. "Citybus", "KMB", for jointly-operated routes only.
     *
     * Note that the message "KMB cycle" is also provided by Citybus / NWFB as well
     *
     * @var string
     */
    public $providingCompany;
    /**
     * e.g. "KMB cycle"
     *
     * @var string
     */
    public $message;
    /**
     * e.g. "Distance: 1.2km, Slightly congested (proceeding to Camp Street )"
     *
     * @var string
     */
    public $description;
    /**
     * @var string
     */
    public $congestionColour;
    /**
     * @var Rdv
     */
    public $rdv;
    /**
     * @var string
     */
    public $status;
    /**
     * @var string
     */
    public $destination;
}
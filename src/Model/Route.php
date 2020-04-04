<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Model;

class Route {
    public const COMPANY_CTB = 'CTB';
    public const COMPANY_NWFB = 'NWFB';
    public const COMPANY_KMB = 'KMB';
    public const COMPANY_LWB = 'LWB';

    public const BRAND_CITYFLYER = 1;
    public const BRAND_CITYBUS = 2;
    public const BRAND_RICKSHAW = 3;
    public const BRAND_NWFB = 4;

    public const OUTBOUND = 'O';
    public const INBOUND = 'I';

    public function __construct(
        string $company
        , string $number
        , string $destinationCode
        , int $numberOfWays
        , string $from
        , string $to
        , int $brand
        , string $id
        , int $defaultVariant
        , string $bound
        , string $serviceType
        , ?string $jointCompany
        , int $fromStop
        , int $toStop
        , bool $isSplit
    ) {
        $this->company = $company;
        $this->number = $number;
        $this->destinationCode = $destinationCode;
        $this->numberOfWays = $numberOfWays;
        $this->from = $from;
        $this->to = $to;
        $this->brand = $brand;
        $this->id = $id;
        $this->defaultVariant = $defaultVariant;
        $this->bound = $bound;
        $this->serviceType = $serviceType;
        $this->jointCompany = $jointCompany;
        $this->fromStop = $fromStop;
        $this->toStop = $toStop;
        $this->isSplit = $isSplit;
    }

    /** @var string CTB or NWFB */
    public $company;
    /** @var string e.g. 1P, 970 */
    public $number;
    /** @var string 3-letter code */
    public $destinationCode;
    /** @var int 0 for circular, 1 for one way, 2 for two ways */
    public $numberOfWays;
    /** @var string The departing place */
    public $from;
    /** @var string The arriving place */
    public $to;
    /** @var int The brand the route belongs to */
    public $brand;
    /** @var string The identifier for querying the API, normally in the form like 4X--Wah_Fu_(South) */
    public $id;
    /** @var int The default variant ID */
    public $defaultVariant;
    /** @var string O for outbound, I for inbound */
    public $bound;
    /** @var string e.g. Monday to Friday only */
    public $serviceType;
    /** @var string|null jointly-operated company, e.g. KMB */
    public $jointCompany;
    /** @var int The initial stop sequence in the default variant */
    public $fromStop;
    /** @var int The final stop sequence in the default variant */
    public $toStop;
    /** @var bool is the route a circular route split into 2 directions */
    public $isSplit;
}
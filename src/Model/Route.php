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
        , string $destination_code
        , int $number_of_ways
        , string $from
        , string $to
        , int $brand
        , string $id
        , int $default_variant
        , string $bound
        , string $service_type
        , ?string $joint_company
        , int $from_stop
        , int $to_stop
        , bool $is_split
    ) {
        $this->company = $company;
        $this->number = $number;
        $this->destination_code = $destination_code;
        $this->number_of_ways = $number_of_ways;
        $this->from = $from;
        $this->to = $to;
        $this->brand = $brand;
        $this->id = $id;
        $this->default_variant = $default_variant;
        $this->bound = $bound;
        $this->service_type = $service_type;
        $this->joint_company = $joint_company;
        $this->from_stop = $from_stop;
        $this->to_stop = $to_stop;
        $this->is_split = $is_split;
    }

    /** @var string CTB or NWFB */
    public $company;
    /** @var string e.g. 1P, 970 */
    public $number;
    /** @var string 3-letter code */
    public $destination_code;
    /** @var int 0 for circular, 1 for one way, 2 for two ways */
    public $number_of_ways;
    /** @var string The departing place */
    public $from;
    /** @var string The arriving place */
    public $to;
    /** @var int The brand the route belongs to */
    public $brand;
    /** @var string The identifier for querying the API, normally in the form like 4X--Wah_Fu_(South) */
    public $id;
    /** @var int The default variant ID */
    public $default_variant;
    /** @var string O for outbound, I for inbound */
    public $bound;
    /** @var string e.g. Monday to Friday only */
    public $service_type;
    /** @var string|null jointly-operated company, e.g. KMB */
    public $joint_company;
    /** @var int The initial stop sequence in the default variant */
    public $from_stop;
    /** @var int The final stop sequence in the default variant */
    public $to_stop;
    /** @var bool is the route a circular route split into 2 directions */
    public $is_split;
}
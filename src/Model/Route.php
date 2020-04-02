<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Model;

class Route {
    const COMPANY_CITYBUS = 'CTB';
    const COMPANY_NWFB = 'NWFB';

    const BRAND_CITYFLYER = 1;
    const BRAND_CITYBUS = 2;
    const BRAND_NWFB = 4;

    const OUTBOUND = 'O';
    const INBOUND = 'I';

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
    /** @var string jointly-operated company, e.g. KMB */
    public $joint_company;
    /** @var string The initial stop sequence in the default variant */
    public $from_stop;
    /** @var string The final stop sequence in the default variant */
    public $to_stop;
    /** @var bool is the route a circular route split into 2 directions */
    public $is_split;
}
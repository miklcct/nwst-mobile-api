<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Model;

class RouteVariant {
    public const COLOUR_RED = 'R';
    public const COLOUR_GREEN = 'G';

    public function __construct(
        int $serial
        , string $colour
        , Rdv $rdv
        , string $description
        , VariantInfo $variantInfo
        , ?string $jointCompany
    ) {
        $this->serial = $serial;
        $this->colour = $colour;
        $this->rdv = $rdv;
        $this->description = $description;
        $this->id = $variantInfo;
        $this->jointCompany = $jointCompany;
    }

    /**
     * @var int The number of the variant shown in the app
     */
    public $serial;
    /**
     * @var string 'R' for red (not in service), and 'G' for green (in service)
     */
    public $colour;
    /**
     * @var Rdv RDV composed with the route, destination and serial, e.g. 14-MAH-2
     */
    public $rdv;
    /**
     * @var string Textual description of the variant, e.g. "Normal routeing"
     */
    public $description;
    /**
     * @var VariantInfo A unique identifier used for querying the API, such as NWFB***14-MAH-2***1***30***10405***O
     */
    public $id;
    /**
     * @var string|null
     */
    public $jointCompany;
}
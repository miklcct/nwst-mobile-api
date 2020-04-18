<?php
declare(strict_types=1);

namespace Miklcct\Nwst\Model;

class StopInfo {
    /**
     * StopInfo constructor.
     * @param string $name
     * @param string $standId
     * @param RouteInStop[] $routesInService
     * @param RouteInStop[] $routesNotInService
     */
    public function __construct(string $name, string $standId, array $routesInService, array $routesNotInService) {
        $this->name = $name;
        $this->standId = $standId;
        $this->routesInService = $routesInService;
        $this->routesNotInService = $routesNotInService;
    }

    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $standId;
    /**
     * @var RouteInStop[]
     */
    public $routesInService;
    /**
     * @var RouteInStop[]
     */
    public $routesNotInService;
}
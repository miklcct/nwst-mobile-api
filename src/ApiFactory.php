<?php
declare(strict_types=1);

namespace Miklcct\Nwst;

use GuzzleHttp\ClientInterface;
use stdClass;
use function json_decode;
use const JSON_THROW_ON_ERROR;

class ApiFactory {
    public const SECRET_URL = 'https://miklcct.com/NwfbSecret.json';

    public function __construct(ClientInterface $client) {
        $this->client = $client;
    }

    public function __invoke(int $language = Api::TRADITIONAL_CHINESE, ?string $appid = NULL, ?string $syscode5 = NULL) {
        $secret = NULL;
        if ($appid !== NULL || $syscode5 !== NULL) {
            $secret = $this->getSecret();
        }
        return new Api($this->client, $appid ?? $secret->appid, $syscode5 ?? $secret->syscode5, $language);
    }

    public function getSecret() : stdClass {
        return json_decode(
            $this->client->request('GET', static::SECRET_URL)->getBody()->__toString()
            , FALSE
            , 512
            , JSON_THROW_ON_ERROR
        );
    }

    /**
     * @var ClientInterface
     */
    private $client;
}
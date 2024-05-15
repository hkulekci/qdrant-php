<?php
/**
 * Builder
 *
 * @since     Jan 2024
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Http;

use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Qdrant\Config;

class Builder
{
    /**
     * @var ClientInterface|null
     */
    protected $client;

    public function __construct(ClientInterface $client = null)
    {
        $this->client = $client ?: Psr18ClientDiscovery::find();
    }

    public function build(Config $config): Transport
    {
        return new Transport(
            $this->client,
            $config
        );
    }
}
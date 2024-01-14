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
    protected ClientInterface $baseClient;

    public function getClient(): ClientInterface
    {
        if (empty($this->baseClient)) {
            $this->baseClient = Psr18ClientDiscovery::find();
        }

        return $this->baseClient;
    }

    public function build(Config $config): Transport
    {
        return new Transport(
            $this->getClient(),
            $config
        );
    }
}
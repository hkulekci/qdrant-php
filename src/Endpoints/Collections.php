<?php
/**
 * Collections
 *
 * https://qdrant.tech/documentation/collections/
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints;

use Qdrant\Response;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\UpdateCollection;

class Collections extends AbstractEndpoint
{
    public function get(): Response
    {
        return $this->client->execute('GET', '/collections');
    }

    public function create(string $name, CreateCollection $params): Response
    {
        return $this->client->execute('PUT', '/collections/' . $name, $params->toArray());
    }

    public function info(string $name): Response
    {
        return $this->client->execute('GET', '/collections/' . $name);
    }

    public function cluster(string $name): Response
    {
        return $this->client->execute('GET', '/collections/' . $name . '/cluster');
    }

    public function delete(string $name): Response
    {
        return $this->client->execute('DELETE', '/collections/' . $name);
    }

    public function update(string $name, UpdateCollection $options): Response
    {
        return $this->client->execute('PATCH', '/collections/' . $name, $options->toArray());
    }

    public function aliases(): Aliases
    {
        return (new Aliases($this->client))->setCollectionName($this->collectionName);
    }

    public function points(): Points
    {
        return (new Points($this->client))->setCollectionName($this->collectionName);
    }
}
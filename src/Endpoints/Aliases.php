<?php
/**
 * Aliases
 *
 * https://qdrant.tech/documentation/collections/#collection-aliases
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\AliasActions;
use Qdrant\Response;

class Aliases extends AbstractEndpoint
{
    public function actions(AliasActions $actions): Response
    {
        return $this->client->execute('POST', '/collections/aliases', ['actions' => $actions->toArray()]);
    }

    public function allAliases(): Response
    {
        return $this->client->execute('GET', '/aliases');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function aliases(): Response
    {
        return $this->client->execute('GET', '/collections/'.$this->getCollectionName().'/aliases');
    }
}
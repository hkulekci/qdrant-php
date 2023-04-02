<?php
/**
 * https://qdrant.tech/documentation/search/
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints\Collections\Points;

use Qdrant\Endpoints\AbstractEndpoint;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\SearchRequest;
use Qdrant\Response;

class Search extends AbstractEndpoint
{
    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(SearchRequest $searchParams): Response
    {
        return $this->client->execute(
            $this->createRequest(
                'POST',
                'collections/' . $this->collectionName . '/points/search',
                $searchParams->toArray()
            )
        );
    }
}
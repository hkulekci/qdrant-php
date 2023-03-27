<?php
/**
 * https://qdrant.tech/documentation/search/
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints;

use Qdrant\Models\Request\SearchRequest;
use Qdrant\Models\VectorStruct;
use Qdrant\Response;

class Search extends AbstractEndpoint
{
    public function __invoke(SearchRequest $searchParams): Response
    {
        return $this->client->execute(
            'POST',
            'collections/' . $this->collectionName . '/points/search',
            $searchParams->toArray()
        );
    }
}
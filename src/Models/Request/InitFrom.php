<?php
/**
 * Init From
 *
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

class InitFrom implements RequestModel
{
    public function __construct(protected string $collection)
    {
    }

    public function toArray(): array
    {
        return [
            'collection' => $this->collection,
        ];
    }
}

<?php
/**
 * Init From
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

class InitFrom implements RequestModel
{
    protected string $collection;

    public function __construct(string $collection)
    {
        $this->collection = $collection;
    }

    public function toArray(): array
    {
        return [
            'collection' => $this->collection
        ];
    }
}
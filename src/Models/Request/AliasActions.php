<?php
/**
 * AliasActions
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

class AliasActions
{
    /**
     * @var mixed[]
     */
    protected $actions;

    public function add(string $alias, string $collection): void
    {
        $this->actions[] = [
            'create_alias' => [
                'alias_name' => $alias,
                'collection_name' => $collection,
            ]
        ];
    }

    public function delete(string $alias): void
    {
        $this->actions[] = [
            'delete_alias' => [
                'alias_name' => $alias,
            ]
        ];
    }

    public function toArray(): array
    {
        return $this->actions;
    }
}
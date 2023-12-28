<?php
/**
 * Operation
 *
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionUpdate;

interface Operation
{
    public function getKey(): string;
    public function toArray(): array;
}
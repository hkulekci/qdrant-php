<?php
/**
 * ModelInterface
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

interface RequestModel
{
    public function toArray(): array;
}
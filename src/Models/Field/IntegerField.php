<?php
/**
 * Integer
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Field;

class IntegerField implements FieldSchema
{
    public function schema(): string
    {
        return 'integer';
    }
}
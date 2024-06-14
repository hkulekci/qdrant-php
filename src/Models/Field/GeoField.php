<?php
/**
 * Geo
 *
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Field;

class GeoField implements FieldSchema
{
    public function schema(): string
    {
        return 'keyword';
    }
}

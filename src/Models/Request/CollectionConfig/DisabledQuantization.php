<?php
/**
 * DisabledQuantization
 *
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

class DisabledQuantization implements QuantizationConfig
{
    public function toArray(): array
    {
        return [
            'Disabled'
        ];
    }
}
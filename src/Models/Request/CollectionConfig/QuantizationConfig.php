<?php
/**
 * QuantizationConfig.php
 *
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

interface QuantizationConfig
{
    public function toArray(): array;
}
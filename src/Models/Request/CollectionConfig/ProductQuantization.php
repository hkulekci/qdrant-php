<?php
/**
 * ProductQuantization
 *
 * @since     Oct 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

class ProductQuantization implements QuantizationConfig
{
    public function __construct(protected string $compression, protected ?bool $alwaysRam = null)
    {
    }

    public function toArray(): array
    {
        $product = [
            'compression' => $this->compression,
        ];

        if ($this->alwaysRam !== null)
        {
            $product['always_ram'] = $this->alwaysRam;
        }

        return [
            'product' => $product,
        ];
    }
}

<?php
/**
 * ScalarQuantization
 *
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

class ScalarQuantization implements QuantizationConfig
{
    public function __construct(protected string $type, protected ?float $quantile = null, protected ?bool $alwaysRam = null)
    {
    }

    public function toArray(): array
    {
        $scalar = [
            'type' => $this->type
        ];

        if ($this->quantile !== null) {
            $scalar['quantile'] = $this->quantile;
        }

        if ($this->alwaysRam !== null) {
            $scalar['always_ram'] = $this->alwaysRam;
        }

        return [
            'scalar' => $scalar
        ];
    }
}
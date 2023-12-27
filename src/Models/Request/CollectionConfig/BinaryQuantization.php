<?php
/**
 * BinaryQuantization
 *
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

class BinaryQuantization implements QuantizationConfig
{
    /**
     * @var bool|null
     */
    protected $alwaysRam;

    public function __construct(?bool $alwaysRam = null)
    {
        $this->alwaysRam = $alwaysRam;
    }

    public function toArray(): array
    {
        $binary = [];

        if ($this->alwaysRam !== null) {
            $binary['always_ram'] = $this->alwaysRam;
        }

        return [
            'binary' => $binary ?: new \stdClass(),
        ];
    }
}
<?php
/**
 * ScalarQuantization
 *
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

use Qdrant\Exception\InvalidArgumentException;

class ScalarQuantization implements QuantizationConfig
{
    public const TYPE_INT8 = 'int8';

    /**
     * @param bool|null $alwaysRam Deprecated since Qdrant 1.19, use $memory instead.
     * @param string|null $memory One of the Memory::* constants.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected string $type,
        protected ?float $quantile = null,
        protected ?bool $alwaysRam = null,
        protected ?string $memory = null
    ) {
        Memory::assertValid($memory);
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

        if ($this->memory !== null) {
            $scalar['memory'] = $this->memory;
        }

        return [
            'scalar' => $scalar
        ];
    }
}

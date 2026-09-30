<?php
/**
 * ProductQuantization
 *
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

use Qdrant\Exception\InvalidArgumentException;

class ProductQuantization implements QuantizationConfig
{
    public const COMPRESSION_X4 = 'x4';
    public const COMPRESSION_X8 = 'x8';
    public const COMPRESSION_X16 = 'x16';
    public const COMPRESSION_X32 = 'x32';
    public const COMPRESSION_X64 = 'x64';

    /**
     * @param bool|null $alwaysRam Deprecated since Qdrant 1.19, use $memory instead.
     * @param string|null $memory One of the Memory::* constants.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected string $compression,
        protected ?bool $alwaysRam = null,
        protected ?string $memory = null
    ) {
        Memory::assertValid($memory);
    }

    public function toArray(): array
    {
        $product = [
            'compression' => $this->compression
        ];

        if ($this->alwaysRam !== null) {
            $product['always_ram'] = $this->alwaysRam;
        }

        if ($this->memory !== null) {
            $product['memory'] = $this->memory;
        }

        return [
            'product' => $product
        ];
    }
}

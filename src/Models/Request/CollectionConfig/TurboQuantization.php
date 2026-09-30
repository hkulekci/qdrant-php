<?php
/**
 * TurboQuantization
 *
 * TurboQuant quantization, available since Qdrant 1.18.
 *
 * https://qdrant.tech/documentation/guides/quantization/
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

use Qdrant\Exception\InvalidArgumentException;

class TurboQuantization implements QuantizationConfig
{
    public const BITS_1 = 'bits1';
    public const BITS_1_5 = 'bits1_5';
    public const BITS_2 = 'bits2';
    public const BITS_4 = 'bits4';

    /**
     * @param string|null $bits Number of bits per dimension, one of the BITS_* constants. Server default is used if null.
     * @param string|null $memory One of the Memory::* constants.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(protected ?string $bits = null, protected ?string $memory = null)
    {
        if ($bits !== null && !in_array($bits, [self::BITS_1, self::BITS_1_5, self::BITS_2, self::BITS_4], true)) {
            throw new InvalidArgumentException('Invalid bits value for TurboQuant quantization');
        }
        Memory::assertValid($memory);
    }

    public function toArray(): array
    {
        $turbo = [];

        if ($this->bits !== null) {
            $turbo['bits'] = $this->bits;
        }

        if ($this->memory !== null) {
            $turbo['memory'] = $this->memory;
        }

        return [
            'turbo' => $turbo ?: new \stdClass(),
        ];
    }
}

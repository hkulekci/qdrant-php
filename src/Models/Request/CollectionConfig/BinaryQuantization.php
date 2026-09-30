<?php
/**
 * BinaryQuantization
 *
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

use Qdrant\Exception\InvalidArgumentException;

class BinaryQuantization implements QuantizationConfig
{
    public const ENCODING_ONE_BIT = 'one_bit';
    public const ENCODING_TWO_BITS = 'two_bits';
    public const ENCODING_ONE_AND_HALF_BITS = 'one_and_half_bits';

    public const QUERY_ENCODING_DEFAULT = 'default';
    public const QUERY_ENCODING_BINARY = 'binary';
    public const QUERY_ENCODING_SCALAR_4BITS = 'scalar4bits';
    public const QUERY_ENCODING_SCALAR_8BITS = 'scalar8bits';

    /**
     * @param bool|null $alwaysRam Deprecated since Qdrant 1.19, use $memory instead.
     * @param string|null $encoding Storage encoding, one of the ENCODING_* constants.
     * @param string|null $queryEncoding Asymmetric query encoding, one of the QUERY_ENCODING_* constants.
     * @param string|null $memory One of the Memory::* constants.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected ?bool $alwaysRam = null,
        protected ?string $encoding = null,
        protected ?string $queryEncoding = null,
        protected ?string $memory = null
    ) {
        Memory::assertValid($memory);
    }

    public function toArray(): array
    {
        $binary = [];

        if ($this->alwaysRam !== null) {
            $binary['always_ram'] = $this->alwaysRam;
        }

        if ($this->encoding !== null) {
            $binary['encoding'] = $this->encoding;
        }

        if ($this->queryEncoding !== null) {
            $binary['query_encoding'] = $this->queryEncoding;
        }

        if ($this->memory !== null) {
            $binary['memory'] = $this->memory;
        }

        return [
            'binary' => $binary ?: new \stdClass(),
        ];
    }
}

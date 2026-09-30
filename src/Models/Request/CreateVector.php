<?php
/**
 * CreateVector
 *
 * Configuration of a named vector to add to an existing collection, available since Qdrant 1.18.
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Exception\InvalidArgumentException;

class CreateVector implements RequestModel
{
    private function __construct(protected string $type, protected array $config)
    {
    }

    /**
     * @param string $distance One of the VectorParams::DISTANCE_* constants.
     * @param string|null $datatype One of the VectorParams::DATATYPE_* constants.
     * @param string|null $multivectorComparator e.g. VectorParams::MULTIVECTOR_COMPARATOR_MAX_SIM
     *
     * @throws InvalidArgumentException
     */
    public static function dense(
        int $size,
        string $distance,
        ?string $datatype = null,
        ?string $multivectorComparator = null
    ): CreateVector {
        $params = new VectorParams($size, $distance);
        if ($datatype !== null) {
            $params->setDatatype($datatype);
        }
        if ($multivectorComparator !== null) {
            $params->setMultivectorConfig($multivectorComparator);
        }

        return new self('dense', $params->toArray());
    }

    /**
     * @param string|null $modifier One of the SparseVectorParams::MODIFIER_* constants.
     * @param string|null $datatype One of the VectorParams::DATATYPE_* constants.
     */
    public static function sparse(?string $modifier = null, ?string $datatype = null): CreateVector
    {
        return new self('sparse', array_filter([
            'modifier' => $modifier,
            'datatype' => $datatype,
        ], static fn($value) => $value !== null));
    }

    public function toArray(): array
    {
        return [
            $this->type => $this->config ?: new \stdClass(),
        ];
    }
}

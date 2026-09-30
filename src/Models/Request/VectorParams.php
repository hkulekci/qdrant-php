<?php
/**
 * VectorParams
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CollectionConfig\DisabledQuantization;
use Qdrant\Models\Request\CollectionConfig\HnswConfig;
use Qdrant\Models\Request\CollectionConfig\Memory;
use Qdrant\Models\Request\CollectionConfig\QuantizationConfig;

class VectorParams implements RequestModel
{
    public const DISTANCE_COSINE = 'Cosine';
    public const DISTANCE_EUCLID = 'Euclid';
    public const DISTANCE_DOT = 'Dot';
    public const DISTANCE_MANHATTAN = 'Manhattan';

    public const DATATYPE_FLOAT32 = 'float32';
    public const DATATYPE_FLOAT16 = 'float16';
    public const DATATYPE_UINT8 = 'uint8';
    /** TurboQuant 4-bit as primary vector storage, original vectors are not kept. Available since Qdrant 1.19. */
    public const DATATYPE_TURBO4 = 'turbo4';

    public const MULTIVECTOR_COMPARATOR_MAX_SIM = 'max_sim';

    protected ?HnswConfig $hnswConfig = null;
    protected ?QuantizationConfig $quantizationConfig = null;
    protected ?bool $onDisk = null;
    protected ?string $memory = null;
    protected ?string $datatype = null;
    protected ?string $multivectorComparator = null;

    /**
     * @param $distance string [Cosine, Euclid, Dot, Manhattan]

     * @throws InvalidArgumentException
     */
    public function __construct(protected int $size, protected string $distance)
    {
        if (!in_array($distance, [self::DISTANCE_COSINE, self::DISTANCE_DOT, self::DISTANCE_EUCLID, self::DISTANCE_MANHATTAN])) {
            throw new InvalidArgumentException('Invalid distance for Vector Param');
        }
    }

    /**
     * Custom HNSW params for this vector, overrides the collection level config.
     */
    public function setHnswConfig(HnswConfig $hnswConfig): VectorParams
    {
        $this->hnswConfig = $hnswConfig;

        return $this;
    }

    /**
     * Custom quantization params for this vector, overrides the collection level config.
     */
    public function setQuantizationConfig(QuantizationConfig $quantizationConfig): VectorParams
    {
        $this->quantizationConfig = $quantizationConfig;

        return $this;
    }

    /**
     * @deprecated Since Qdrant 1.19, use setMemory() instead.
     */
    public function setOnDisk(bool $onDisk): VectorParams
    {
        $this->onDisk = $onDisk;

        return $this;
    }

    /**
     * Memory usage strategy for the vector storage, one of the Memory::* constants.
     *
     * @throws InvalidArgumentException
     */
    public function setMemory(string $memory): VectorParams
    {
        Memory::assertValid($memory);
        $this->memory = $memory;

        return $this;
    }

    /**
     * Datatype used to store the vectors, one of the DATATYPE_* constants.
     *
     * @throws InvalidArgumentException
     */
    public function setDatatype(string $datatype): VectorParams
    {
        $datatypes = [self::DATATYPE_FLOAT32, self::DATATYPE_FLOAT16, self::DATATYPE_UINT8, self::DATATYPE_TURBO4];
        if (!in_array($datatype, $datatypes, true)) {
            throw new InvalidArgumentException('Invalid datatype for Vector Param');
        }
        $this->datatype = $datatype;

        return $this;
    }

    /**
     * Store multiple vectors per point (e.g. ColBERT) compared with the given comparator.
     */
    public function setMultivectorConfig(string $comparator = self::MULTIVECTOR_COMPARATOR_MAX_SIM): VectorParams
    {
        $this->multivectorComparator = $comparator;

        return $this;
    }

    public function toArray(): array
    {
        $data = [
            'size' => $this->size,
            'distance' => $this->distance,
        ];

        if ($this->hnswConfig !== null) {
            $data['hnsw_config'] = $this->hnswConfig->toArray();
        }
        if ($this->quantizationConfig instanceof DisabledQuantization) {
            $data['quantization_config'] = 'Disabled';
        } elseif ($this->quantizationConfig !== null) {
            $data['quantization_config'] = $this->quantizationConfig->toArray();
        }
        if ($this->onDisk !== null) {
            $data['on_disk'] = $this->onDisk;
        }
        if ($this->memory !== null) {
            $data['memory'] = $this->memory;
        }
        if ($this->datatype !== null) {
            $data['datatype'] = $this->datatype;
        }
        if ($this->multivectorComparator !== null) {
            $data['multivector_config'] = ['comparator' => $this->multivectorComparator];
        }

        return $data;
    }
}

<?php
/**
 * WalConfig
 *
 * @since     Dec 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\RequestModel;

class WalConfig implements RequestModel
{
    /**
     * @var int|null
     */
    protected $walCapacityMb;

    /**
     * @var int|null
     */
    protected $walSegmentsAhead;

    public function setWalCapacityMb(?int $walCapacityMb): WalConfig
    {
        if ($walCapacityMb < 1) {
            throw new InvalidArgumentException('wal_capacity_mb should be bigger than 1');
        }
        $this->walCapacityMb = $walCapacityMb;

        return $this;
    }

    public function setWalSegmentsAhead(?int $walSegmentsAhead): WalConfig
    {
        if ($walSegmentsAhead < 0) {
            throw new InvalidArgumentException('wal_segments_ahead should be bigger than 0');
        }
        $this->walSegmentsAhead = $walSegmentsAhead;

        return $this;
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->walCapacityMb) {
            $data['wal_capacity_mb'] = $this->walCapacityMb;
        }
        if ($this->walSegmentsAhead) {
            $data['wal_segments_ahead'] = $this->walSegmentsAhead;
        }

        return $data;
    }
}
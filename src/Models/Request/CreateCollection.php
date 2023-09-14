<?php
/**
 * Create Collection
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

class CreateCollection implements RequestModel
{
    /**
     * @var array
     */
    protected $vectors;

    /**
     * @var int|null
     */
    protected $shardNumber = null;

    /**
     * @var int|null
     */
    protected $replicationFactor = null;

    /**
     * @var int|null
     */
    protected $writeConsistencyFactor = null;

    /**
     * @var bool|null
     */
    protected $onDiskPayload = null;

    /**
     * @var InitFrom|null
     */
    protected $initFrom = null;

    public function addVector(VectorParams $vectorParams, string $name = null): CreateCollection
    {
        if ($name !== null) {
            $this->vectors[$name] = $vectorParams->toArray();
        } else {
            $this->vectors = $vectorParams->toArray();
        }

        return $this;
    }

    public function setShardNumber(int $shardNumber): CreateCollection
    {
        $this->shardNumber = $shardNumber;

        return $this;
    }

    public function setReplicationFactor(int $replicationFactor): CreateCollection
    {
        $this->replicationFactor = $replicationFactor;

        return $this;
    }

    public function setWriteConsistencyFactor(int $writeConsistencyFactor): CreateCollection
    {
        $this->writeConsistencyFactor = $writeConsistencyFactor;

        return $this;
    }

    public function setOnDiskPayload(bool $onDiskPayload): CreateCollection
    {
        $this->onDiskPayload = $onDiskPayload;

        return $this;
    }

    public function setInitFrom(InitFrom $initFrom): CreateCollection
    {
        $this->initFrom = $initFrom;

        return $this;
    }



    public function toArray(): array
    {
        $data = [];
        if ($this->vectors) {
            $data['vectors'] = $this->vectors;
        }
        if ($this->shardNumber !== null) {
            $data['shard_number'] = $this->shardNumber;
        }
        if ($this->replicationFactor !== null) {
            $data['replication_factor'] = $this->replicationFactor;
        }
        if ($this->writeConsistencyFactor !== null) {
            $data['write_consistency_factor'] = $this->writeConsistencyFactor;
        }
        if ($this->onDiskPayload !== null) {
            $data['on_disk_payload'] = $this->onDiskPayload;
        }
        if ($this->initFrom !== null) {
            $data['init_from'] = $this->initFrom->toArray();
        }

        return $data;
    }
}
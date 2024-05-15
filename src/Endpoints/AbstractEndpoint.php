<?php
/**
 * AbstractEndpoint
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints;

use Qdrant\ClientInterface;
use Qdrant\Exception\InvalidArgumentException;

abstract class AbstractEndpoint
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string|null
     */
    protected $collectionName;

    use HttpFactoryTrait;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getCollectionName(): string
    {
        if ($this->collectionName === null) {
            throw new InvalidArgumentException('You need to specify the collection name');
        }
        return $this->collectionName;
    }

    /**
     * @return static
     */
    public function setCollectionName(?string $collectionName)
    {
        $this->collectionName = $collectionName;

        return $this;
    }
}
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
    protected ?string $collectionName = null;

    use HttpFactoryTrait;

    public function __construct(protected ClientInterface $client)
    {
    }

    public function setCollectionName(?string $collectionName): static
    {
        $this->collectionName = $collectionName;

        return $this;
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
}
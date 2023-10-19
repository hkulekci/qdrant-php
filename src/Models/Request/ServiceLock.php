<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

class ServiceLock implements RequestModel
{

    public function __construct(protected bool $write, protected ?string $errorMessage = null)
    {
    }

    public function toArray(): array
    {
        return [
            'write' => $this->write,
            'error_message' => $this->errorMessage
        ];
    }
}
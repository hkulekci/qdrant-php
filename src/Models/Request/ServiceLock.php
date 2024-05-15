<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

class ServiceLock implements RequestModel
{

    /**
     * @var bool
     */
    protected $write;
    /**
     * @var string|null
     */
    protected $errorMessage;

    public function __construct(bool $write, ?string $errorMessage = null)
    {
        $this->write = $write;
        $this->errorMessage = $errorMessage;
    }

    public function toArray(): array
    {
        return [
            'write' => $this->write,
            'error_message' => $this->errorMessage
        ];
    }
}
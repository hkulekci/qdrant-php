<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

class CreateIndex implements RequestModel
{
    public function __construct(protected string $fieldName, protected ?array $fieldSchema = null)
    {
    }

    public function toArray(): array
    {
        return array_filter([
            'field_name' => $this->fieldName,
            'field_schema' => $this->fieldSchema
        ]);
    }
}
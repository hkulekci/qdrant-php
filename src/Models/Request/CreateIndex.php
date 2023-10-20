<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

class CreateIndex implements RequestModel
{
    public function __construct(protected string $fieldName, protected string $fieldSchema, protected array $schemaParams = [])
    {
    }

    public function toArray(): array
    {
        // TODO: schema params is missing
        return [
            'field_name' => $this->fieldName,
            'field_schema' => $this->fieldSchema
        ];
    }
}
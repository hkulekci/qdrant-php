<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

class CreateIndex implements RequestModel
{
    protected string $fieldName;
    protected ?array $fieldSchema;

    public function __construct(string $fieldName, array|string|null $fieldSchema = null)
    {
        if (is_string($fieldSchema)) {
            $this->fieldSchema = [
                'type' => $fieldSchema
            ];
        } else {
            $this->fieldSchema = $fieldSchema;
        }

        $this->fieldName = $fieldName;
    }

    public function toArray(): array
    {
        return array_filter([
            'field_name' => $this->fieldName,
            'field_schema' => $this->fieldSchema
        ]);
    }
}

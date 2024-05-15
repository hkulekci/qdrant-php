<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

class CreateIndex implements RequestModel
{
    /**
     * @var string
     */
    protected $fieldName;

    /**
     * @var string
     */
    protected $fieldSchema;

    /**
     * @var mixed[]
     */
    protected $schemaParams = [];

    public function __construct(string $fieldName, string $fieldSchema, array $schemaParams = [])
    {
        $this->fieldName = $fieldName;
        $this->fieldSchema = $fieldSchema;
        $this->schemaParams = $schemaParams;
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
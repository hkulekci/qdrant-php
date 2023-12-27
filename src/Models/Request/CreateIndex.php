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
     * @var array
     */
    protected $schemaParams = [];

    public function __construct(string $fieldName, string $fieldSchema, array $schemaParams = [])
    {
        $this->schemaParams = $schemaParams;
        $this->fieldSchema = $fieldSchema;
        $this->fieldName = $fieldName;
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
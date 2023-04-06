<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class AbstractCondition
{
    protected string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }
}
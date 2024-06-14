<?php
/**
 * @since     Apr 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

class AbstractCondition
{
    public function __construct(protected string $key)
    {
    }
}

<?php

namespace Qdrant\Tests\Unit\Models\Traits;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Qdrant\Models\Traits\ProtectedPropertyAccessor;

class ProtectedPropertyAccessorTest extends TestCase
{
    public function testAccessProtectedProperty()
    {
        $mock = new class {
            use ProtectedPropertyAccessor;

            protected $exampleProperty = "value";
        };

        $this->assertEquals("value", $mock->getExampleProperty());
    }

    public function testAccessNonExistentProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Property 'nonExistentProperty' does not exist");

        $mock = new class {
            use ProtectedPropertyAccessor;
        };

        $unused = $mock->getNonExistentProperty();
    }

    public function testAccessPublicProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Access to property 'publicProperty' is not allowed");

        $mock = new class {
            use ProtectedPropertyAccessor;

            public $publicProperty = "publicValue";
        };

        $unused = $mock->getPublicProperty();
    }

    public function testAccessPrivateProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Access to property 'privateProperty' is not allowed");

        $mock = new class {
            use ProtectedPropertyAccessor;

            private $privateProperty = "privateValue";
        };

        $unused = $mock->getPrivateProperty();
    }
}

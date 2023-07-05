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

        $this->assertEquals("value", $mock->exampleProperty);
    }

    public function testAccessNonExistentProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Property 'nonExistentProperty' does not exist");

        $mock = new class {
            use ProtectedPropertyAccessor;
        };

        $unused = $mock->nonExistentProperty;
    }

    public function testAccessPublicProperty()
    {
        $mock = new class {
            use ProtectedPropertyAccessor;

            public $publicProperty = "publicValue";
        };

        $this->assertEquals("publicValue", $mock->publicProperty);
    }

    public function testAccessPrivateProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Access to property 'privateProperty' is not allowed");

        $mock = new class {
            use ProtectedPropertyAccessor;

            private $privateProperty = "privateValue";
        };

        $private = $mock->privateProperty;
    }

}
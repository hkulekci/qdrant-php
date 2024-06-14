<?php
/**
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\GeoBoundingBox;

class GeoBoundingBoxTest extends TestCase
{
    public function testGeoFilterWithEmptyData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('BoundingBox expects "bottom_right" key');

        $filter = new GeoBoundingBox('location', []);
    }

    public function testGeoFilterWithMissingData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('BoundingBox expects "top_left" key');

        $filter = new GeoBoundingBox(
            'location',
            [
                'bottom_right' => [],
            ]
        );
    }

    public function testGeoFilterWithMissingLatData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('All geo locations need to provide "lat" key');

        $filter = new GeoBoundingBox(
            'location',
            [
                'bottom_right' => [],
                'top_left' => [],
            ]
        );
    }

    public function testGeoFilterWithMissingLonData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('All geo locations need to provide "lon" key');

        $filter = new GeoBoundingBox(
            'location',
            [
                'bottom_right' => [
                    'lat' => 0.2,
                ],
                'top_left' => [
                    'lat' => 0.1,
                ],
            ]
        );
    }

    public function testGeoFilterWithValidData(): void
    {
        $filter = new GeoBoundingBox(
            'location',
            [
                'bottom_right' => [
                    'lat' => 0.2,
                    'lon' => 0.1,
                ],
                'top_left' => [
                    'lat' => 0.1,
                    'lon' => 0.3,
                ],
            ]
        );

        $this->assertEquals(
            [
                'key' => 'location',
                'geo_bounding_box' => [
                    'bottom_right' => [
                        'lat' => 0.2,
                        'lon' => 0.1,
                    ],
                    'top_left' => [
                        'lat' => 0.1,
                        'lon' => 0.3,
                    ],
                ],
            ],
            $filter->toArray()
        );
    }
}

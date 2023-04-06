<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\GeoRadius;

class GeoRadiusTest extends TestCase
{
    public function testGeoRadiusFilterWithEmptyData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Radius expects "center" key');

        $filter = new GeoRadius('location', []);
    }

    public function testGeoRadiusFilterWithMissingData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Radius expects "radius" key');

        $filter = new GeoRadius(
            'location',
            [
                'center' => []
            ]
        );
    }

    public function testGeoRadiusFilterWithMissingLatData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Radius center parameter expected lat and lon');

        $filter = new GeoRadius(
            'location',
            [
                'center' => [],
                'radius' => 100
            ]
        );
    }

    public function testGeoRadiusFilterWithValidData(): void
    {
        $filter = new GeoRadius(
            'location',
            [
                'center' => [
                    'lat' => 0.2,
                    'lon' => 0.1
                ],
                'radius' => 100
            ]
        );


        $this->assertEquals(
            [
                'key' => 'location',
                'geo_radius' => [
                    'center' => [
                        'lat' => 0.2,
                        'lon' => 0.1
                    ],
                    'radius' => 100
                ]
            ],
            $filter->toArray()
        );
    }
}
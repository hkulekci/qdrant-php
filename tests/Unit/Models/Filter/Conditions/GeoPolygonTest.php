<?php
/**
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\GeoPolygon;

class GeoPolygonTest extends TestCase
{
    public function testGeoPolygonFilterWithEmptyData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Exteriors required!');

        $filter = new GeoPolygon('location', []);
    }

    public static function missingLatLonDataProvider(): array
    {
        return [
            [
                [
                    [
                        'lat' => 0,
                    ],
                ],
                [],
            ],
            [
                [
                    [
                        'lat' => 0,
                        'lon' => 0,
                    ],
                    [
                        'lat' => 1,
                        'lon' => 1,
                    ],
                ],
                [
                    [
                        'lon' => 1,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider missingLatLonDataProvider
     */
    public function testGeoPolygonFilterWithMissingData(array $exterior, array $interiors): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Each point of polygon needs lat and lon parameters');

        $filter = new GeoPolygon(
            'location',
            $exterior,
            $interiors
        );
    }

    public function testGeoPolygonFilter(): void
    {
        $filter = new GeoPolygon(
            'location',
            [
                [
                    'lat' => 1,
                    'lon' => 2,
                ],
            ],
            [
                [
                    'lat' => 9,
                    'lon' => 10,
                ],
            ]
        );

        $this->assertEquals([
            'key' => 'location',
            'geo_polygon' => [
                'exterior' => [
                    [
                        'lat' => 1,
                        'lon' => 2,
                    ],
                ],
                'interiors' => [
                    [
                        'lat' => 9,
                        'lon' => 10,
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    public function testGeoPolygonFilterWithEmptyInteriors(): void
    {
        $filter = new GeoPolygon(
            'location',
            [
                [
                    'lat' => 1,
                    'lon' => 2,
                ],
            ]
        );

        $this->assertEquals([
            'key' => 'location',
            'geo_polygon' => [
                'exterior' => [
                    [
                        'lat' => 1,
                        'lon' => 2,
                    ],
                ],
                'interiors' => [],
            ],
        ], $filter->toArray());
    }
}

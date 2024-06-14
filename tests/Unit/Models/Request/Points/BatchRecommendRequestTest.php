<?php
/**
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\Points;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\Points\BatchRecommendRequest;
use Qdrant\Models\Request\Points\RecommendRequest;

class BatchRecommendRequestTest extends TestCase
{
    public function testBasicRecommendRequest(): void
    {
        $request = new BatchRecommendRequest([
            new RecommendRequest([100, 101], [110]),
            new RecommendRequest([101, 102], [112]),
        ]);

        $this->assertEquals([
            'searches' => [
                [
                    'positive' => [100, 101],
                    'negative' => [110],
                ],
                [
                    'positive' => [101, 102],
                    'negative' => [112],
                ],
            ],
        ], $request->toArray());
    }
}

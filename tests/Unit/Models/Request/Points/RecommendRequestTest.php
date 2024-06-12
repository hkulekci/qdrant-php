<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\Points;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\MatchExcept;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Request\Points\RecommendRequest;

class RecommendRequestTest extends TestCase
{
    public function testBasicRecommendRequest(): void
    {
        $request = new RecommendRequest([100, 101], [110]);

        $this->assertEquals([
            'positive' => [100, 101],
            'negative' => [110],
        ], $request->toArray());
    }

    public function testRecommendRequestWithFilter(): void
    {
        $request = (new RecommendRequest([100, 101], [110]))
            ->setFilter(
                (new Filter())->addMust((new MatchExcept('foo', ['bar'])))
            );

        $this->assertEquals([
            'positive' => [100, 101],
            'negative' => [110],
            'filter' => [
                'must' => [
                    [
                        'key' => 'foo',
                        'match' => [
                            'except' => ['bar']
                        ]
                    ]
                ]
            ]
        ], $request->toArray());
    }

    public function testRecommendRequestWithScoreThreshold(): void
    {
        $request = (new RecommendRequest([100, 101], [110]))
            ->setFilter(
                (new Filter())->addMust((new MatchExcept('foo', ['bar'])))
            )
            ->setScoreThreshold(1.0);

        $this->assertEquals([
            'positive' => [100, 101],
            'negative' => [110],
            'score_threshold'=> 1.0,
            'filter' => [
                'must' => [
                    [
                        'key' => 'foo',
                        'match' => [
                            'except' => ['bar']
                        ]
                    ]
                ]
            ]
        ], $request->toArray());
    }

    public function testRecommendRequestWithOffset(): void
    {
        $request = (new RecommendRequest([100, 101], [110]))
            ->setOffset(1);

        $this->assertEquals([
            'positive' => [100, 101],
            'negative' => [110],
            'offset'=> 1,
        ], $request->toArray());
    }

    public function testRecommendRequestWithUsing(): void
    {
        $request = (new RecommendRequest([100, 101], [110]))
            ->setUsing('foo');

        $this->assertEquals([
            'positive' => [100, 101],
            'negative' => [110],
            'using'=> 'foo',
        ], $request->toArray());
    }

    public function testRecommendRequestWithInvalidStrategy(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid strategy for recommendation.');
        $request = (new RecommendRequest([100, 101], [110]))
            ->setUsing('foo')
            ->setStrategy('bar');

        $this->assertEquals([
            'positive' => [100, 101],
            'negative' => [110],
            'using'=> 'foo',
        ], $request->toArray());
    }

    public function testRecommendRequestWithLimitAndSharKey(): void
    {
        $request = (new RecommendRequest([100, 101], [110]))
            ->setLimit(10)
            ->setOffset(1)
            ->setShardKey('shard_key');

        $this->assertEquals([
            'positive' => [100, 101],
            'negative' => [110],
            'limit'=> 10,
            'offset'=> 1,
            'shard_key' => 'shard_key'
        ], $request->toArray());
    }

    public function testRecommendRequestWithLimitAndStrategy(): void
    {
        $request = (new RecommendRequest([100, 101], [110]))
            ->setLimit(10)
            ->setOffset(1)
            ->setStrategy(RecommendRequest::STRATEGY_AVERAGE_VECTOR);

        $this->assertEquals([
            'positive' => [100, 101],
            'negative' => [110],
            'limit'=> 10,
            'offset'=> 1,
            'strategy' => RecommendRequest::STRATEGY_AVERAGE_VECTOR
        ], $request->toArray());
    }
}
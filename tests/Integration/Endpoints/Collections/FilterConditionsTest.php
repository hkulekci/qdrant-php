<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints\Collections;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\HasVector;
use Qdrant\Models\Filter\Condition\IsNull;
use Qdrant\Models\Filter\Condition\MatchPrefix;
use Qdrant\Models\Filter\Condition\Slice;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\Request\CreateIndex;
use Qdrant\Models\Request\ScrollRequest;
use Qdrant\Models\VectorStruct;
use Qdrant\Tests\Integration\AbstractIntegration;

class FilterConditionsTest extends AbstractIntegration
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createCollections('sample-collection');
        $points = [];
        foreach (['electronics', 'electric', 'books', 'beauty', null, 'election', 'garden', 'games'] as $i => $category) {
            $points[] = [
                'id' => $i + 1,
                'vector' => new VectorStruct([1, 2, $i], 'image'),
                'payload' => ['category' => $category],
            ];
        }
        $this->getCollections('sample-collection')->index()->create(
            new CreateIndex('category', ['type' => 'keyword', 'prefix' => true]),
            ['wait' => 'true']
        );
        $this->getCollections('sample-collection')->points()
            ->upsert(PointsStruct::createFromArray($points), ['wait' => 'true']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testMatchPrefix(): void
    {
        $filter = (new Filter())->addMust(new MatchPrefix('category', 'elec'));

        $this->assertEquals([1, 2, 6], $this->scrollIds($filter));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSlicesPartitionAllPoints(): void
    {
        $ids = [];
        for ($index = 0; $index < 3; $index++) {
            $sliceIds = $this->scrollIds((new Filter())->addMust(new Slice($index, 3)));
            $this->assertEmpty(array_intersect($ids, $sliceIds), 'Slices must not overlap');
            $ids = array_merge($ids, $sliceIds);
        }
        sort($ids);

        $this->assertEquals(range(1, 8), $ids);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testIsNullAndHasVector(): void
    {
        $this->assertEquals([5], $this->scrollIds((new Filter())->addMust(new IsNull('category'))));
        $this->assertEquals(range(1, 8), $this->scrollIds((new Filter())->addMust(new HasVector('image'))));
        $this->assertEquals([], $this->scrollIds((new Filter())->addMust(new HasVector('text'))));
    }

    private function scrollIds(Filter $filter): array
    {
        $response = $this->getCollections('sample-collection')->points()
            ->scroll((new ScrollRequest())->setFilter($filter)->setLimit(100));
        $ids = array_column($response['result']['points'], 'id');
        sort($ids);

        return $ids;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->getCollections('sample-collection')->delete();
    }
}

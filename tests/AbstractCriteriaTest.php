<?php

declare(strict_types=1);

namespace spaceonfire\Criteria;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Webmozart\Expression\Expr;

abstract class AbstractCriteriaTest extends TestCase
{
    /**
     * @var CriteriaInterface
     */
    protected $criteria;

    abstract protected function createCriteria(): CriteriaInterface;

    protected function setUp(): void
    {
        $this->criteria = $this->createCriteria();
    }

    public function testOrderBy(): void
    {
        $this->criteria->orderBy(['key1' => SORT_DESC, 'key2' => SORT_ASC]);
        self::assertEquals(['key1' => SORT_DESC, 'key2' => SORT_ASC], $this->criteria->getOrderBy());
    }

    public function testOrderByInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->criteria->orderBy(['key1' => 0, 'key2' => 'asc']);
    }

    public function testOffset(): void
    {
        self::assertEquals(0, $this->criteria->getOffset());
        $this->criteria->offset(25);
        self::assertEquals(25, $this->criteria->getOffset());
    }

    public function testInclude(): void
    {
        $this->criteria->include(['relName', 'chained.relName']);
        self::assertEquals(['relName', 'chained.relName'], $this->criteria->getInclude());
    }

    public function testLimit(): void
    {
        self::assertEquals(null, $this->criteria->getLimit());
        $this->criteria->limit(25);
        self::assertEquals(25, $this->criteria->getLimit());
    }

    public function testWhere(): void
    {
        self::assertEquals(null, $this->criteria->getWhere());
        $expression = Expr::property('fieldName', Expr::equals('test'));
        $this->criteria->where($expression);
        self::assertEquals($expression, $this->criteria->getWhere());
    }

    public function testMergeOverrideAll(): void
    {
        $expression = Expr::property('fieldName', Expr::equals('test'));

        $newCriteria = $this->createCriteria()
            ->limit(50)
            ->offset(20)
            ->include(['includeA'])
            ->orderBy(['orderAsc' => SORT_ASC])
            ->where($expression);

        $mergedCriteria = $this->criteria->merge($newCriteria);

        self::assertEquals(50, $mergedCriteria->getLimit());
        self::assertEquals(20, $mergedCriteria->getOffset());
        self::assertEquals(['includeA'], $mergedCriteria->getInclude());
        self::assertEquals(['orderAsc' => SORT_ASC], $mergedCriteria->getOrderBy());
        self::assertEquals($expression, $mergedCriteria->getWhere());
    }

    public function testMergeOverridePartially(): void
    {
        $this->criteria->offset(50);

        $newCriteria = $this->createCriteria()->offset(20);

        $mergedCriteria = $this->criteria->merge($newCriteria);

        self::assertEquals($this->criteria->getLimit(), $mergedCriteria->getLimit());
        self::assertEquals(20, $mergedCriteria->getOffset());
        self::assertEquals($this->criteria->getInclude(), $mergedCriteria->getInclude());
        self::assertEquals($this->criteria->getOrderBy(), $mergedCriteria->getOrderBy());
        self::assertEquals($this->criteria->getWhere(), $mergedCriteria->getWhere());
    }

    public function testExpr(): void
    {
        $this->criteria::expr();
        self::assertTrue(true);
    }
}

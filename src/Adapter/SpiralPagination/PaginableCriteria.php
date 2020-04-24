<?php

declare(strict_types=1);

namespace spaceonfire\Criteria\Adapter\SpiralPagination;

use spaceonfire\Criteria\Criteria;
use spaceonfire\Criteria\CriteriaInterface;
use spaceonfire\Criteria\Expression\ExpressionBuilder;
use Spiral\Pagination\PaginableInterface;
use Spiral\Pagination\Paginator;
use Spiral\Pagination\PaginatorInterface;
use Webmozart\Expression\Expression;

class PaginableCriteria implements CriteriaInterface, PaginableInterface
{
    /**
     * @var CriteriaInterface
     */
    private $criteria;

    /**
     * PaginableCriteria constructor.
     * @param CriteriaInterface|null $criteria original criteria to proxy
     */
    public function __construct(?CriteriaInterface $criteria = null)
    {
        $this->criteria = $criteria ?? new Criteria();
    }

    /**
     * Export original criteria
     * @return CriteriaInterface
     */
    public function export(): CriteriaInterface
    {
        return $this->criteria;
    }

    /**
     * @return PaginatorInterface|Paginator
     */
    public function makePaginator(): PaginatorInterface
    {
        if ($this->getLimit() !== null && $this->getLimit() > 0) {
            $page = (int)($this->getOffset() / $this->getLimit()) + 1;
            return (new Paginator($this->getLimit()))->withPage($page);
        }

        return new Paginator();
    }

    /**
     * @param string $methodName
     * @param mixed[] $arguments
     * @return mixed
     */
    private function proxyCall(string $methodName, array $arguments = [])
    {
        return call_user_func_array([$this->criteria, $methodName], $arguments);
    }

    /**
     * @inheritDoc
     */
    public function getWhere(): ?Expression
    {
        return $this->proxyCall(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function where(?Expression $expression): CriteriaInterface
    {
        $this->proxyCall(__FUNCTION__, func_get_args());
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOrderBy(): array
    {
        return $this->proxyCall(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function orderBy(array $orderBy): CriteriaInterface
    {
        $this->proxyCall(__FUNCTION__, func_get_args());
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOffset(): int
    {
        return $this->proxyCall(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function offset(?int $offset): CriteriaInterface
    {
        $this->proxyCall(__FUNCTION__, func_get_args());
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLimit(): ?int
    {
        return $this->proxyCall(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function limit(?int $limit): CriteriaInterface
    {
        $this->proxyCall(__FUNCTION__, func_get_args());
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getInclude(): array
    {
        return $this->proxyCall(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function include(array $include): CriteriaInterface
    {
        $this->proxyCall(__FUNCTION__, func_get_args());
        return $this;
    }

    /**
     * @inheritDoc
     * The original criteria will not be changed, a new one will be returned instead.
     */
    public function merge(CriteriaInterface $criteria): CriteriaInterface
    {
        $clone = clone $this;
        $clone->criteria = $clone->proxyCall(__FUNCTION__, func_get_args());
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public static function expr(): ExpressionBuilder
    {
        return Criteria::expr();
    }
}
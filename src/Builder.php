<?php

namespace Nacoma\ElasticQueryBuilder;

use Nacoma\ElasticQueryBuilder\Builder\AggregateBuilder;
use Nacoma\ElasticQueryBuilder\Builder\BoolContextQuery;
use Nacoma\ElasticQueryBuilder\Builder\ConstraintBuilder;
use Closure;
use JsonSerializable;

class Builder implements JsonSerializable
{
    private readonly ConstraintBuilder $constraintBuilder;
    private readonly AggregateBuilder $aggregateBuilder;

    public function __construct(
        public string $index,
        ?ConstraintBuilder $query = null,
        ?AggregateBuilder $aggregateBuilder = null,
        public int $size = 0,
        public int $from = 0,
    ) {
        $this->constraintBuilder = $query ?: new ConstraintBuilder();
        $this->aggregateBuilder = $aggregateBuilder ?: new AggregateBuilder();
    }

    public function size(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function from(int $from): self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param Closure(AggregateBuilder): void $callback
     */
    public function aggregate(Closure $callback): self
    {
        $callback($this->aggregateBuilder);
        return $this;
    }

    public function index(string $index): self
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @param Closure(ConstraintBuilder): void $callback
     */
    public function bool(Closure $callback): self
    {
        $callback($this->constraintBuilder);
        return $this;
    }

    /**
     * @param Closure(BoolContextQuery): void $callback
     */
    public function filter(Closure $callback): self
    {
        $this->bool(function (ConstraintBuilder $query) use ($callback) {
            $query->filter($callback);
        });

        return $this;
    }

    /**
     * @param Closure(BoolContextQuery): void $callback
     */
    public function must(Closure $callback): self
    {
        $this->bool(function (ConstraintBuilder $query) use ($callback) {
            $query->must($callback);
        });

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return [
            'index' => $this->index,
            'size' => $this->size,
            'from' => $this->from,
            'body' => [
                'query' => ['bool' => $this->constraintBuilder],
                'aggregations' => $this->aggregateBuilder,
            ],
        ];
    }
}
<?php

namespace Nacoma\ElasticQueryBuilder\Builder;

class AggregateBuilder extends FluentAggregate
{
    /**
     * @param array<string, Aggregate> $aggregations
     */
    public function __construct(
        private array $aggregations = [],
    ) {
        //
    }

    public function aggregate(string $name, Aggregate $aggregate): static
    {
        $this->aggregations[$name] = $aggregate;
        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return $this->aggregations;
    }
}
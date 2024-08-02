<?php

namespace Nacoma\ElasticQueryBuilder\Builder;

use Closure;

abstract class FluentAggregate implements Aggregate
{
    abstract public function aggregate(string $name, Aggregate $aggregate): static;
    //

    public function dateHistogram(string $name, string $field, string $calendarInterval, Closure $callback): self
    {
        $aggregate = new DateHistogramAggregate($field, $calendarInterval);
        $callback($aggregate);
        return $this->aggregate($name, $aggregate);
    }

    public function terms(string $name, string $field, \Closure $callback): self
    {
        $aggregate = new TermsAggregate($field);
        $callback($aggregate);
        return $this->aggregate($name, $aggregate);
    }

    public function sum(string $name, string $field): static
    {
        return $this->aggregate($name, new SumAggregate($field));
    }

    public function avg(string $name, string $field): static
    {
        return $this->aggregate($name, new AvgAggregate($field));
    }

    public function cardinality(string $name, string $field, int $precisionThreshold = 3_000): static
    {
        return $this->aggregate($name, new CardinalityAggregate($field, $precisionThreshold));
    }
}
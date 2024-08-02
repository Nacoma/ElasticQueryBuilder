<?php

namespace Nacoma\ElasticQueryBuilder\Builder;

class DateHistogramAggregate extends FluentAggregate
{
    public readonly AggregateBuilder $aggregateBuilder;
    public function __construct(
        public string $field,
        public string $calendarInterval,
        ?AggregateBuilder $aggregateBuilder = null,
    )
    {
        $this->aggregateBuilder = $aggregateBuilder ?: new AggregateBuilder();
    }

    public function aggregate(string $name, Aggregate $aggregate): static
    {
        $this->aggregateBuilder->aggregate($name, $aggregate);
        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'date_histogram' => [
                'field' => $this->field,
                'calendar_interval' => $this->calendarInterval,
            ],
            'aggregations' => $this->aggregateBuilder,
        ];
    }
}
<?php

namespace Nacoma\ElasticQueryBuilder\Builder;

class SumAggregate implements Aggregate
{
    public function __construct(
        public readonly string $field,
    ) {
        //
    }

    /**
     * @return array{sum: array{field: string}}
     */
    public function jsonSerialize(): array
    {
        return [
            'sum' => [
                'field' => $this->field,
            ],
        ];
    }
}
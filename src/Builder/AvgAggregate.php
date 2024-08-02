<?php

namespace Nacoma\ElasticQueryBuilder\Builder;

class AvgAggregate implements Aggregate
{
    public function __construct(
        public readonly string $field,
    ) {
        //
    }

    /**
     * @return array{avg: array{field: string}}
     */
    public function jsonSerialize(): array
    {
        return [
            'avg' => [
                'field' => $this->field,
            ],
        ];
    }
}
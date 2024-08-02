<?php

namespace Nacoma\ElasticQueryBuilder\Builder;

class CardinalityAggregate implements Aggregate
{
    public function __construct(
        public readonly string $field,
        // elastic default
        public readonly int $precisionThreshold = 3_000,
    ) {
        //
    }

    /**
     * @return array{cardinality: array{field: string, precision_threshold: int}}
     */
    public function jsonSerialize(): array
    {
        return [
            'cardinality' => [
                'field' => $this->field,
                'precision_threshold' => $this->precisionThreshold,
            ],
        ];
    }
}
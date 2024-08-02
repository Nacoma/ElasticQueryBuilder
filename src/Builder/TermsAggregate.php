<?php

namespace Nacoma\ElasticQueryBuilder\Builder;

class TermsAggregate extends FluentAggregate
{
    /**
     * @param string $field
     * @param int $size
     * @param array<string, Aggregate> $aggregations
     */
    public function __construct(
        public string $field,
        public int $size = 100,
        public array $aggregations = [],
    ) {
        //
    }

    public function size(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function aggregate(string $name, Aggregate $aggregate): static
    {
        $this->aggregations[$name] = $aggregate;
        return $this;
    }

    /**
     * @return array{terms: array{field: string, size: int}, aggregations: mixed[]}
     */
    public function jsonSerialize(): array
    {
        return [
            'terms' => [
                'field' => $this->field,
                'size' => $this->size,
            ],
            'aggregations' => $this->aggregations,
        ];
    }
}
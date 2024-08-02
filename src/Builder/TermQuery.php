<?php

namespace Nacoma\ElasticQueryBuilder\Builder;

class TermQuery implements Query
{
    public function __construct(
        public readonly string $field,
        public readonly int|string|bool|float $value,
    ) {
        //
    }

    public function jsonSerialize(): mixed
    {
        return [
            'term' => [
                $this->field => $this->value,
            ],
        ];
    }
}

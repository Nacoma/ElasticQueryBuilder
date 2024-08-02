<?php

namespace Nacoma\ElasticQueryBuilder\Builder;

class TermsQuery implements Query
{
    /**
     * @param string $field
     * @param list<scalar> $values
     */
    public function __construct(
        public readonly string $field,
        public readonly array $values,
    ) {
        //
    }

    public function jsonSerialize(): mixed
    {
        return [
            'terms' => [
                $this->field => $this->values,
            ],
        ];
    }
}
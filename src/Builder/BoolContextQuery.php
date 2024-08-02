<?php

namespace Nacoma\ElasticQueryBuilder\Builder;

use DateTimeInterface;

class BoolContextQuery implements Query
{
    /**
     * @param list<Query> $clauses
     */
    public function __construct(
        public array $clauses = [],
    ) {
        //
    }

    public function constrain(Query $clause): self
    {
        $this->clauses[] = $clause;
        return $this;
    }

    public function term(string $field, int|string|bool|float $value): self
    {
        return $this->constrain(new TermQuery($field, $value));
    }

    /**
     * @param list<scalar> $values
     */
    public function terms(string $field, array $values): self
    {
        return $this->constrain(new TermsQuery($field, $values));
    }

    public function range(
        string $field,
        null|int|float|DateTimeInterface $gt = null,
        null|int|float|DateTimeInterface $gte = null,
        null|int|float|DateTimeInterface $lt = null,
        null|int|float|DateTimeInterface $lte = null,
    ): self {
        return $this->constrain(new RangeQuery($field, $gt, $gte, $lt, $lte));
    }

    public function jsonSerialize(): mixed
    {
        return $this->clauses;
    }
}
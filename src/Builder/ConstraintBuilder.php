<?php

namespace Nacoma\ElasticQueryBuilder\Builder;

use Closure;

class ConstraintBuilder implements Query
{
    public BoolContextQuery $must;
    public BoolContextQuery $filter;

    public function __construct(
        ?BoolContextQuery $must = null,
        ?BoolContextQuery $filter = null,
    ) {
        $this->must = $must ?: new BoolContextQuery();
        $this->filter = $filter ?: new BoolContextQuery();
    }

    /**
     * @param Closure(BoolContextQuery): void $callback
     */
    public function must(Closure $callback): self
    {
        $callback($this->must);
        return $this;
    }

    /**
     * @param Closure(BoolContextQuery): void $callback
     */
    public function filter(Closure $callback): self
    {
        $callback($this->filter);
        return $this;
    }

    /**
     * @return array{must?: BoolContextQuery, filter?: BoolContextQuery}
     */
    public function jsonSerialize(): array
    {
        $payload = [];

        if ($this->must->clauses) {
            $payload['must'] = $this->must;
        }

        if ($this->filter->clauses) {
            $payload['filter'] = $this->filter;
        }

        return $payload;
    }
}
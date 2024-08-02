<?php

namespace Nacoma\ElasticQueryBuilder\Builder;


use Carbon\CarbonImmutable;
use DateTimeInterface;

class RangeQuery implements Query
{
    public function __construct(
        public readonly string $field,
        public readonly null|int|float|DateTimeInterface $gt = null,
        public readonly null|int|float|DateTimeInterface $gte = null,
        public readonly null|int|float|DateTimeInterface $lt = null,
        public readonly null|int|float|DateTimeInterface $lte = null,
    ) {
        //
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'range' => [
                $this->field => array_filter([
                    'gte' => $this->gte instanceof DateTimeInterface
                        ? (new CarbonImmutable($this->gte))->toAtomString()
                        : $this->gte,
                    'lte' => $this->lte instanceof DateTimeInterface
                        ? (new CarbonImmutable($this->lte))->toAtomString()
                        : $this->lte,
                    'lt' => $this->lt instanceof DateTimeInterface
                        ? (new CarbonImmutable($this->lt))->toAtomString()
                        : $this->lt,
                    'gt' => $this->gt instanceof DateTimeInterface
                        ? (new CarbonImmutable($this->gt))->toAtomString()
                        : $this->gt,
                ]),
            ],
        ];
    }
}
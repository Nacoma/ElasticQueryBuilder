<?php

use Carbon\CarbonImmutable;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Aggregation;
use Spatie\ElasticsearchQueryBuilder\Aggregations\CardinalityAggregation;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithMissing;
use Spatie\ElasticsearchQueryBuilder\Aggregations\SumAggregation;
use Spatie\ElasticsearchQueryBuilder\Aggregations\TermsAggregation;
use Spatie\ElasticsearchQueryBuilder\Builder;
use Spatie\ElasticsearchQueryBuilder\Queries\BoolQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\RangeQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\TermQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\TermsQuery;

/**
 * Some of the aggregations that we use aren't implemented by spatie. Their implementations are at the bottom.
 */

$client = new Builder(/** elastic client builder not installed in this repo */);

$start = CarbonImmutable::now();
$end = CarbonImmutable::now()->subYear();

$client->addQuery(
    BoolQuery::create()
        ->add(
            RangeQuery::create('started_at')
                ->gte($start)
                ->lte($end),
            'must',
        )
        ->add(
            TermsQuery::create('id', [1, 2, 3]),
            'must',
        )
        ->add(
            TermQuery::create('status', 'active'),
            'must',
        )
);

$client->addAggregation(
    TermsAggregation::create('by_customer', 'customer_id')
        ->aggregation(
            SumAggregation::create('total_revenue', 'revenue')
        )
        ->aggregation(
        // avg aggregation not implemented by package
            AvgAggregation::create('average_revenue', 'revenue')
        )
        ->aggregation(
        // precision threshold not supported
            CardinalityAggregation::create('num_unique_products_purchased', 'sku')
        )
);

$client->addAggregation(
    DateHistogram::create('by_day', '@timestamp', '1d')
        ->aggregation(SumAggregation::create('daily_revenue', 'revenue'))
);


/**
 * Not implemented by Spatie
 */
class AvgAggregation extends Aggregation
{
    use WithMissing;

    protected string $field;
    public function __construct(string $name, string $field)
    {
        $this->name = $name;
        $this->field = $field;
    }

    public static function create(string $name, string $field): self
    {
        return new self($name, $field);
    }

    public function payload(): array
    {
        $parameters = [
            'field' => $this->field,
        ];

        if ($this->missing) {
            $parameters['missing'] = $this->missing;
        }

        return [
            'sum' => $parameters,
        ];
    }
}

/**
 * Not implemented by Spatie.
 */
class DateHistogram extends Aggregation
{
    use WithAggregations;

    protected string $field;
    protected string $interval;

    public function __construct(string $name, string $field, string $interval)
    {
        $this->name = $name;
        $this->field = $field;
        $this->interval = $interval;
    }

    public static function create(string $name, string $field, string $interval): self
    {
        return new self($name, $field, $interval);
    }

    public function payload(): array
    {
        $parameters = [
            'date_histogram' => [
                'field' => $this->field,
                'calendar_interval' => $this->interval,
            ],
        ];

        if (!$this->aggregations->isEmpty()) {
            $parameters['aggregations'] = $this->aggregations->toArray();
        }

        return $parameters;
    }
}


<?php

use Carbon\CarbonImmutable;
use Spatie\ElasticsearchQueryBuilder\Aggregations\CardinalityAggregation;
use Spatie\ElasticsearchQueryBuilder\Aggregations\SumAggregation;
use Spatie\ElasticsearchQueryBuilder\Aggregations\TermsAggregation;
use Spatie\ElasticsearchQueryBuilder\Builder;
use Spatie\ElasticsearchQueryBuilder\Queries\BoolQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\RangeQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\TermQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\TermsQuery;

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
        // avg aggregation not implemented
        ->aggregation(
        // precision threshold not supported
            CardinalityAggregation::create('num_unique_products_purchased', 'sku')
        )
);

//date histogram not supported.
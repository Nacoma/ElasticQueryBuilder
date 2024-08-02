<?php

use Carbon\CarbonImmutable;
use Nacoma\ElasticQueryBuilder\Builder;

$start = CarbonImmutable::now();
$end = CarbonImmutable::now()->subYear();

$builder = new Builder(
    index: 'my-index',
);

$builder->must(function (Builder\BoolContextQuery $query) use ($start, $end) {
    $query->range(
        field: '@timestamp',
        gte: $start,
        lte: $end,
    )
        ->terms(
            field: 'id',
            values: [1, 2, 3],
        )
        ->term(
            field: 'status',
            value: 'active'
        );
});

$builder->aggregate(function (Builder\AggregateBuilder $query) {
    $query->terms('by_customer', 'customer_id', function (Builder\TermsAggregate $query) {
        $query->sum('total_revenue', 'revenue')
            ->avg('average_revenue', 'revenue')
            ->cardinality('num_unique_products_purchased', 'sku')
            ->size(10); // top ten customers, probably defaults to sorting by doc count, dunno
    });
});

$builder->aggregate(function (Builder\AggregateBuilder $query) {
    $query->dateHistogram('by_day', '@timestamp', '1d', function (Builder\DateHistogramAggregate $query) {
        $query->sum('daily_revenue', 'revenue');
    });
});

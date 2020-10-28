<?php
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;


    echo Highcharts::widget([
        'id' => 'yearlyPieChart',
        'scripts' => [
            'modules/exporting',
            'themes/grid-light',
        ],
        'options' => [
            'chart' => [
                'type' => 'pie',
            ],
            'title' => [
                'text' => 'Customers by Business Nature ' ,
            ],
            'credits' => false,
            'labels' => [
                'items' => [
                    [
                        'html' => '',
                        'style' => [
                            'left' => '50px',
                            'top' => '18px',
                            'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                        ],
                    ],
                ],
            ],
            'series' => [
                [
                    'name' => 'Total',
                    'data' => $data,
                    'size' => '100%',
                    'showInLegend' => true,
                    'dataLabels' => [
                        'enabled' => true,
                    ],
                ],
            ],
        ]
    ]);
?>
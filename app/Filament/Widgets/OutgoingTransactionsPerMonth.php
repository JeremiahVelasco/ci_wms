<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class OutgoingTransactionsPerMonth extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'outgoingTransactionsPerMonth';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Outgoing transactions per month';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $data = $this->getTransactionsData();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Transactions',
                    'data' => $data['counts'],
                ],
            ],
            'xaxis' => [
                'categories' => $data['months'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#f59e0b'],
        ];
    }

    /**
     * Get transactions data for the current year
     *
     * @return array
     */
    private function getTransactionsData(): array
    {
        $year = Carbon::now()->year;
        $months = [];
        $counts = [];

        // Get all months for the current year
        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::createFromDate($year, $month, 1);
            $months[] = $date->format('M');

            // Count transactions for this month
            $count = Transaction::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('status', 2)
                ->count();

            $counts[] = $count;
        }

        return [
            'months' => $months,
            'counts' => $counts,
        ];
    }
}

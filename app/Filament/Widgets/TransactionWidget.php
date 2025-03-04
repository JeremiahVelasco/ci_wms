<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionWidget extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'transactionWidget';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Transaction History';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $data = $this->getData();

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'Incoming Transactions',
                    'data' => $data['incoming'],
                ],
                [
                    'name' => 'Outgoing Transactions',
                    'data' => $data['outgoing'],
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
                'min' => 0,
                'forceNiceScale' => true,
            ],
            'colors' => ['#10b981', '#f59e0b'], // Green for incoming, amber for outgoing
            'stroke' => [
                'curve' => 'smooth',
                'width' => 2,
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'right',
                'fontFamily' => 'inherit',
            ],
            'tooltip' => [
                'shared' => true,
                'intersect' => false,
            ],
            'grid' => [
                'borderColor' => '#e5e7eb',
                'row' => [
                    'opacity' => 0.5,
                ],
            ],
        ];
    }

    /**
     * Get the transaction data for the chart
     * 
     * @return array
     */
    protected function getData(): array
    {
        // Get the current year
        $year = Carbon::now()->year;

        // Initialize arrays for months and transaction counts
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $incomingCounts = array_fill(0, 12, 0);
        $outgoingCounts = array_fill(0, 12, 0);

        // Query for incoming transactions (status = 1)
        $incomingTransactions = Transaction::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', $year)
            ->where('status', 1)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Query for outgoing transactions (status = 2)
        $outgoingTransactions = Transaction::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', $year)
            ->where('status', 2)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Populate the counts arrays
        foreach ($incomingTransactions as $transaction) {
            $monthIndex = $transaction->month - 1; // Convert to 0-based index
            $incomingCounts[$monthIndex] = $transaction->count;
        }

        foreach ($outgoingTransactions as $transaction) {
            $monthIndex = $transaction->month - 1; // Convert to 0-based index
            $outgoingCounts[$monthIndex] = $transaction->count;
        }

        return [
            'months' => $months,
            'incoming' => $incomingCounts,
            'outgoing' => $outgoingCounts,
        ];
    }
}

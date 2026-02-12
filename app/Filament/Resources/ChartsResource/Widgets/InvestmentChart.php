<?php

namespace App\Filament\Resources\ChartsResource\Widgets;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Investment;
use Filament\Widgets\ChartWidget;

class InvestmentChart extends ChartWidget
{
    protected static ?string $heading = 'Investment Chart';
    public ?string $filter = 'year';
    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $data = Trend::model(Investment::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();
 
        return [
            'datasets' => [
                [
                    'label' => 'Investments',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }
    protected function getType(): string
    {
        return 'line';
    }
}

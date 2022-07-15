<?php

namespace App\HealthCheck;

use App\Models\HealthCheck;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BaseHealthCheck
{
    public $endPoint;
    public $data;
    public $health_check_name;

    public function __construct()
    {
        $this->health_check_name = lcfirst(substr(strrchr(get_class($this), "\\"), 1));
    }

    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
    }

    public function parseData(Collection $health_checks)
    {

    }

    public function getDateRange($range = null)
    {
        switch ($range ?? request()->date_range) {
            case 'h':
                return [
                    'begin_range' => Carbon::now()->subHour(), // Last hour
                    'step' => 5, // 5 minutes
                    'format' => 'H:i',
                ];
            case 'd':
                return [
                    'begin_range' => Carbon::now()->subDay(), // Last day
                    'step' => 5, // 5 minutes
                    'format' => 'H:i',
                ];
            case 'w':
                return [
                    'begin_range' => Carbon::now()->subMonth(), // Last week
                    'step' => 1 * 60 * 6, // 6 hour
                    'format' => 'd M H:i',
                ];
            case '2w':
                return [
                    'begin_range' => Carbon::now()->subMonth(), // Last 2 weeks
                    'step' => 1 * 60 * 12, // 12 hours
                    'format' => 'd M H:i',
                ];
            case 'm':
                return [
                    'begin_range' => Carbon::now()->subMonth(), // Last month
                    'step' => 1 * 60 * 24, // 1 day
                    'format' => 'd M',
                ];
            case '3m':
                return [
                    'begin_range' => Carbon::now()->subMonths(3), // Last 3 months
                    'step' => 1 * 60 * 24, // 1 day
                    'format' => 'd M',
                ];
            case 'y':
                return [
                    'begin_range' => Carbon::now()->subYear(), // Last Year
                    'step' => 1 * 60 * 24 * 30, // 1 month
                    'format' => 'M',
                ];

            default:
                return [
                    'begin_range' => null,
                    'step' => 1 * 60, // 1 minute
                    'format' => 'd/m/Y H:i',
                ];
        }
    }

    public function getData()
    {
        $dateRange = $this->getDateRange();

        // Auto detect the date range to apply
        if (!$dateRange['begin_range']) {

            // Get the first date a health check has been recorded
            $dateRange['begin_range'] = HealthCheck::where('end_point_id', $this->endPoint->id)
                ->where('name', $this->health_check_name)
                ->first()->finished_at;

            // Detect the good range to apply depending of the difference between now
            $diffInMinutes = $dateRange['begin_range']->diffInMinutes();
            if ($diffInMinutes < 1 * 60 /* 1 hour */) $dateRange = $this->getDateRange('h');
            else if ($diffInMinutes < 1 * 60 * 24 /* 1 day */) $dateRange = $this->getDateRange('d');
            else if ($diffInMinutes < 1 * 60 * 24 * 30 /* one month */) $dateRange = $this->getDateRange('m');
            else $dateRange = $this->getDateRange('y');
        }

        // Get the data for only the desired range
        $health_checks = HealthCheck::where('end_point_id', $this->endPoint->id)
            ->where('name', $this->health_check_name)
            ->when($dateRange['begin_range'], fn($query, $begin_range) => $query->where('finished_at', '>=', $begin_range))
            ->get();

        // Parse the data to calculate the avg on the good date
        // For exemple: convert "200 - 280ms" --> (int)280
        $parsed_health_checks = $this->parseData($health_checks);

        // Create the group of data depending of the range
        $grouped_health_checks = new Collection();
        $now = Carbon::now();
        while ($dateRange['begin_range'] <= $now) {
            $grouped_health_checks->push([
                'label' => $dateRange['begin_range']->format($dateRange['format']),
                'value' => $parsed_health_checks->where('finished_at', '>=', $dateRange['begin_range'])
                    ->where('finished_at', '<', $dateRange['begin_range']->copy()->addMinutes($dateRange['step']))
                    ->avg('stat_value'),
            ]);

            $dateRange['begin_range']->addMinutes($dateRange['step']);
        }

        return $grouped_health_checks;
    }

    public function view()
    {
        return view('health_checks.'.$this->health_check_name, [
            'datas' => $this->getData() ?? []
        ]);
    }
}
